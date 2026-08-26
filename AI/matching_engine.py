import re

import numpy as np
from sklearn.metrics.pairwise import cosine_similarity

# =========================================================
# قاموس الترادفات
# =========================================================
synonyms = {

    "python": [
        "python",
        "py",
        "scripting",
        "django",
        "fastapi",
        "flask"
    ],

    "docker": [
        "docker",
        "container",
        "containers",
        "devops",
        "kubernetes",
        "k8s"
    ],

    "kubernetes": [
        "k8s",
        "kubernetes",
        "devops",
        "containerization",
        "deployment"
    ],

    "aws": [
        "aws",
        "cloud",
        "amazon web services",
        "devops",
        "infrastructure"
    ],

    "natural language processing": [
        "nlp",
        "text processing",
        "llm",
        "ai engineer",
        "language models",
        "ai"
    ],

    "machine learning": [
        "ml",
        "scikit-learn",
        "predictive modeling",
        "ai engineer",
        "data scientist"
    ],

    "deep learning": [
        "dl",
        "neural networks",
        "pytorch",
        "tensorflow",
        "ai engineer"
    ],

    "system administrator": [
        "sysadmin",
        "devops",
        "it support",
        "infrastructure",
        "linux",
        "network administrator"
    ]
}

# بناء خريطة عكسية: أي مصطلح (سواء كان المفتاح الأساسي أو
# أحد مرادفاته) يشير إلى المجموعة الكاملة لمرادفاته
token_to_synonym_group = {}

for canonical_term, related_terms in synonyms.items():

    full_group = set(related_terms) | {canonical_term}

    for term in full_group:
        token_to_synonym_group.setdefault(
            term,
            set()
        ).update(full_group)


# =========================================================
# مطابقة المهارات والمسمى الوظيفي
# =========================================================
def compute_single_skill_scores(
    candidate_skills_str,
    candidate_title,
    job_titles,
    job_descs
):

    n = len(job_titles)

    skill_scores = np.zeros(n, dtype=np.float32)

    raw_skills = str(candidate_skills_str).lower()
    raw_title = str(candidate_title).lower()

    skills_list = [
        s.strip()
        for s in re.split(r'[,|/;]', raw_skills)
        if s.strip()
    ]

    search_tokens = set(skills_list)
    synonym_tokens = set()

    for sk in skills_list:
        if sk in token_to_synonym_group:
            synonym_tokens.update(token_to_synonym_group[sk])

    if raw_title in token_to_synonym_group:
        synonym_tokens.update(token_to_synonym_group[raw_title])
    elif raw_title:
        for t in raw_title.split():
            if t in token_to_synonym_group:
                synonym_tokens.update(token_to_synonym_group[t])
            elif len(t) >= 2:
                synonym_tokens.add(t)

    all_tokens = search_tokens.union(synonym_tokens)

    if not all_tokens:
        return skill_scores

    compiled_tokens = [
        (token, re.compile(r'\b' + re.escape(token) + r'\b'))
        for token in all_tokens
    ]

    for j in range(n):

        title_context = str(job_titles[j]).lower()
        desc_context = str(job_descs[j]).lower()
        full_context = f"{title_context} {desc_context}"

        matches = sum(
            1
            for _, pattern in compiled_tokens
            if pattern.search(full_context)
        )

        base_match = matches / len(all_tokens)

        exact_title_match = any(
            pattern.search(title_context)
            for _, pattern in compiled_tokens
        )

        title_bonus = 0.50 if exact_title_match else 0.0

        skill_scores[j] = min(1.0, (base_match * 0.6) + title_bonus)

    return skill_scores


# =========================================================
# حساب Experience Score
# =========================================================
def calculate_experience_score(candidate_experience, job_description):

    try:
        candidate_experience = float(candidate_experience)
    except (ValueError, TypeError):
        candidate_experience = 0.0

    job_description = str(job_description).lower()

    matches = re.findall(r'(\d+)\+?\s*(?:years?|yrs?)', job_description)

    if not matches:
        return 1.0

    required_experience = max(int(x) for x in matches)

    if required_experience == 0:
        return 1.0

    if candidate_experience >= required_experience:
        return 1.0

    return candidate_experience / required_experience


def combine_final_score(skl, sem, exp):
    """
    Skills = 65%, Semantic = 20%, Experience = 15%.
    When no skill overlap at all, fall back to Semantic 85% + Experience 15%
    so a candidate with zero declared matching skills isn't scored as a flat 0.
    """
    if skl > 0:
        return (skl * 0.65) + (sem * 0.20) + (exp * 0.15)

    return (sem * 0.85) + (exp * 0.15)


def build_candidate_cv_text(
    skills="",
    education="",
    experience_years="",
    about="",
    current_title=""
):
    return f"""Candidate Profile:

Skills:
{skills}

Current Title:
{current_title}

Education:
{education}

Experience Years:
{experience_years}

About:
{about}
"""


def build_job_embedding_text(title, description="", requirements="", skills=None):
    skills = skills or []
    skills_text = ", ".join(skills)
    return (
        f"{title}. {title} role requires {description} {requirements} "
        f"Required skills: {skills_text}"
    ).strip()


def _tokenize_list(raw_str):
    raw = str(raw_str).lower()
    return [s.strip() for s in re.split(r'[,|/;]', raw) if s.strip()]


def _expand_with_synonyms(tokens):
    expanded = set(tokens)
    for t in tokens:
        if t in token_to_synonym_group:
            expanded.update(token_to_synonym_group[t])
    return expanded


SEMANTIC_MATCH_THRESHOLD = 0.55
"""
Cosine-similarity cutoff (all-MiniLM-L6-v2) for treating two skill terms
as equivalent. Calibrated empirically: 'coding'~'programming' = 0.751,
'teamwork'~'collaboration' = 0.636, while genuinely different skills
like 'python'~'java' = 0.450 and unrelated pairs land under 0.30. 0.55
sits above the "related but distinct" band so it doesn't credit a
candidate for a skill they don't actually have.
"""

FREE_TEXT_MATCH_THRESHOLD = 0.30
"""
A single short skill term embedded against an entire job-description
*paragraph* lands in a much lower similarity range than two short terms
compared to each other — e.g. 'git' scores 0.751 against the term
'git' but only ~0.36 against a paragraph that literally contains the
word "Git", because the paragraph embedding averages in many unrelated
concepts. Reusing SEMANTIC_MATCH_THRESHOLD here would make this
fallback path fire almost never; this lower cutoff was picked from the
same kind of literal-mention case scoring ~0.3.
"""


def compute_structured_skill_score(
    model,
    candidate_skills_str,
    required_skills,
    free_text_context="",
):
    """
    How well a candidate's declared skills satisfy a *structured* list
    of required skills (a real job post's linked Skill records) —
    matched *semantically*, not just literally.

    A literal/synonym-dict check runs first (cheap, precise, no model
    call needed for the common case). Anything it can't resolve falls
    back to sentence-embedding cosine similarity, so e.g. a job asking
    for "coding" matches a candidate who wrote "programming" even
    though the words share no characters and neither is in the
    hand-curated synonyms dict above. Literal string/regex matching
    can never catch this — embedding similarity can.

    Deliberately has no "current job title" bonus: JobLens is meant to
    match fresh graduates and career-changers fairly on declared
    skills alone, not favor whoever already happens to hold a job
    title similar to the posting (most applicants who'd benefit most
    from the platform have no matching current title at all). A
    perfect skill match must be able to reach a full 1.0 on its own.

    Real job_posts carry an explicit required-skills list rather than
    only free-form prose, so scoring coverage as matched/len(required)
    is used instead of compute_single_skill_scores()'s
    matched/len(all_candidate_tokens): that denominator is the
    candidate's *entire* synonym-expanded vocabulary (often 15-20+
    tokens once synonyms fan out), which silently drowns out a perfect
    3-for-3 declared-skill match down to ~0.1. A semantic search
    against `free_text_context` (description/requirements) is kept as
    a secondary signal — whichever of the two is stronger wins — so a
    job posting with no selected skills still benefits from prose
    matching the way the original CSV-based scorer did.
    """
    candidate_terms = _tokenize_list(candidate_skills_str)

    if not candidate_terms:
        return 0.0

    candidate_tokens_expanded = _expand_with_synonyms(candidate_terms)
    candidate_embeddings = model.encode(candidate_terms)

    required = [str(s).strip().lower() for s in required_skills if str(s).strip()]

    structured_match = 0.0
    if required:
        matched_count = 0
        unresolved = []

        for req in required:
            if candidate_tokens_expanded & token_to_synonym_group.get(req, {req}):
                matched_count += 1
            else:
                unresolved.append(req)

        if unresolved:
            unresolved_embeddings = model.encode(unresolved)
            sims = cosine_similarity(unresolved_embeddings, candidate_embeddings)
            matched_count += int((sims.max(axis=1) >= SEMANTIC_MATCH_THRESHOLD).sum())

        structured_match = matched_count / len(required)

    free_text_match = 0.0
    if free_text_context and str(free_text_context).strip():
        context_embedding = model.encode([str(free_text_context)])
        sims = cosine_similarity(candidate_embeddings, context_embedding)[:, 0]
        free_text_match = float(np.mean(sims >= FREE_TEXT_MATCH_THRESHOLD))

    return max(structured_match, free_text_match)


def score_jobs_for_candidate(model, candidate, jobs):
    """
    candidate: dict with keys skills, education, experience_years, about, current_title
    jobs: list of dicts with keys id, title, description, requirements, skills (list[str])
    Returns a list of {"job_id": ..., "score": 0-100}, sorted by score descending.
    """
    if not jobs:
        return []

    candidate_cv_text = build_candidate_cv_text(
        skills=candidate.get("skills", ""),
        education=candidate.get("education", ""),
        experience_years=candidate.get("experience_years", ""),
        about=candidate.get("about", ""),
        current_title=candidate.get("current_title", ""),
    )

    cand_embedding = model.encode([candidate_cv_text])

    job_texts = [
        build_job_embedding_text(
            job["title"],
            job.get("description", ""),
            job.get("requirements", "") or "",
            job.get("skills", []),
        )
        for job in jobs
    ]

    job_embeddings = model.encode(job_texts)

    sem_similarities = cosine_similarity(cand_embedding, job_embeddings)[0]
    sem_similarities = np.clip(sem_similarities, 0.0, 1.0)

    # سياق المطابقة النصية الاحتياطي لكل وظيفة: الوصف + المتطلبات
    job_contexts = [
        f"{job.get('description', '')} {job.get('requirements', '') or ''}"
        for job in jobs
    ]

    results = []

    for i, job in enumerate(jobs):
        sem = float(sem_similarities[i])

        skl = compute_structured_skill_score(
            model,
            candidate.get("skills", ""),
            job.get("skills", []),
            job_contexts[i],
        )

        exp = calculate_experience_score(
            candidate.get("experience_years", ""),
            job_contexts[i],
        )

        score = combine_final_score(skl, sem, exp)

        results.append({
            "job_id": job["id"],
            "score": round(score * 100, 2),
        })

    results.sort(key=lambda r: r["score"], reverse=True)

    return results


def score_candidates_for_job(model, job, candidates):
    """
    Inverse of score_jobs_for_candidate: rank several candidates against
    a single real job post (used by the company's "Applicants" page).

    job: dict with keys title, description, requirements, skills (list[str])
    candidates: list of dicts with keys id, skills, education,
                experience_years, about, current_title
    Returns a list of {"candidate_id": ..., "score": 0-100}, sorted descending.
    """
    if not candidates:
        return []

    job_text = build_job_embedding_text(
        job.get("title", ""),
        job.get("description", "") or "",
        job.get("requirements", "") or "",
        job.get("skills", []),
    )

    job_embedding = model.encode([job_text])

    candidate_texts = [
        build_candidate_cv_text(
            skills=candidate.get("skills", ""),
            education=candidate.get("education", ""),
            experience_years=candidate.get("experience_years", ""),
            about=candidate.get("about", ""),
            current_title=candidate.get("current_title", ""),
        )
        for candidate in candidates
    ]

    candidate_embeddings = model.encode(candidate_texts)

    sem_similarities = cosine_similarity(job_embedding, candidate_embeddings)[0]
    sem_similarities = np.clip(sem_similarities, 0.0, 1.0)

    job_context_for_experience = (
        f"{job.get('description', '') or ''} {job.get('requirements', '') or ''}"
    )

    results = []

    for i, candidate in enumerate(candidates):
        sem = float(sem_similarities[i])

        # نفس اتجاه compute_structured_skill_score المستخدم في
        # score_jobs_for_candidate: "هل مهارات هذا المرشح تغطي مهارات
        # الوظيفة المطلوبة؟" — فقط بترتيب حلقة معكوس (وظيفة واحدة،
        # عدة مرشحين بدل مرشح واحد وعدة وظائف)
        skl = compute_structured_skill_score(
            model,
            candidate.get("skills", ""),
            job.get("skills", []),
            job_context_for_experience,
        )

        exp = calculate_experience_score(
            candidate.get("experience_years", ""),
            job_context_for_experience,
        )

        score = combine_final_score(skl, sem, exp)

        results.append({
            "candidate_id": candidate["id"],
            "score": round(score * 100, 2),
        })

    results.sort(key=lambda r: r["score"], reverse=True)

    return results
