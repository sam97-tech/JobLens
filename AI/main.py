import os
import numpy as np
import pandas as pd
from sentence_transformers import SentenceTransformer
from sklearn.metrics.pairwise import cosine_similarity

from matching_engine import (
    compute_single_skill_scores,
    calculate_experience_score,
    combine_final_score,
)

BASE_DIR = os.path.dirname(os.path.abspath(__file__))
DATA_DIR = os.path.join(BASE_DIR, "data")

# =========================================================
# 1. قراءة البيانات وتنظيف الحقول
# =========================================================
print("1. جاري قراءة البيانات وتنظيف الحقول...")

csv_path = os.path.join(DATA_DIR, "resume_dataset_1200.csv")

if not os.path.exists(csv_path):
    raise FileNotFoundError(f"لم يتم العثور على ملف البيانات: {csv_path}")

df = pd.read_csv(csv_path)

required_columns = {"Skills", "Target_Job_Description"}
missing_columns = required_columns - set(df.columns)

if missing_columns:
    raise ValueError(f"الأعمدة التالية مفقودة من ملف البيانات: {missing_columns}")

# تحديد اسم عمود الوظيفة المستهدفة
job_title_col = (
    "Target_Job_Title"
    if "Target_Job_Title" in df.columns
    else "Current_Job_Title"
)

# تنظيف القيم المفقودة
df[job_title_col] = (
    df[job_title_col]
    .fillna("")
    .astype(str)
    .str.strip()
)

df["Target_Job_Description"] = (
    df["Target_Job_Description"]
    .fillna("")
    .astype(str)
    .str.strip()
)

df["Skills"] = (
    df["Skills"]
    .fillna("")
    .astype(str)
    .str.strip()
)

# =========================================================
# 2. تحميل نموذج Sentence Transformer
# =========================================================
print("2. جاري تحميل نموذج Sentence Transformer...")

model = SentenceTransformer("all-MiniLM-L6-v2")

# دمج اسم الوظيفة مع الوصف
job_text = (
    df[job_title_col]
    + ". "
    + df[job_title_col]
    + " role requires "
    + df["Target_Job_Description"]
)

embeddings_cache_path = os.path.join(DATA_DIR, "job_embeddings.npy")

if os.path.exists(embeddings_cache_path):
    print("3. جاري تحميل الـ Embeddings المحفوظة مسبقًا...")
    job_embeddings = np.load(embeddings_cache_path)
else:
    print("3. جاري استخراج الـ Embeddings للوظائف...")
    job_embeddings = model.encode(
        job_text.tolist(),
        show_progress_bar=True
    )
    np.save(embeddings_cache_path, job_embeddings)

# =========================================================
# 3. قاموس الترادفات + 4. مطابقة المهارات + 5. Experience Score
#
# منقولة إلى matching_engine.py لتُستخدم أيضًا من خدمة الـ AI
# التي تعمل على بيانات job_posts الحقيقية (AI/service.py)
# =========================================================
# 6. دالة التقييم والتوصية
# =========================================================
def recommend_jobs_for_candidate(
    skills,
    current_title="",
    field_of_study="",
    previous_jobs="",
    education_level="",
    degrees="",
    experience_years="",
    certifications="",
    top_k=3
):

    # =====================================================
    # بناء نص الـCV
    # =====================================================
    candidate_cv_text = f"""
Candidate Profile:

Skills:
{skills}

Current Job Title:
{current_title}

Field of Study:
{field_of_study}

Previous Job Titles:
{previous_jobs}

Education Level:
{education_level}

Degrees:
{degrees}

Experience Years:
{experience_years}

Certifications:
{certifications}
"""

    # =====================================================
    # Embedding للـCV
    # =====================================================
    cand_embedding = model.encode(
        [candidate_cv_text]
    )

    # =====================================================
    # Semantic Similarity
    # =====================================================
    sem_similarities = cosine_similarity(
        cand_embedding,
        job_embeddings
    )[0]

    # cosine similarity قد تكون قيمتها سالبة نظريًا؛ نحصرها
    # بين 0 و1 حتى لا "تُنقص" النتيجة النهائية بلا معنى
    sem_similarities = np.clip(sem_similarities, 0.0, 1.0)

    # =====================================================
    # Skill Matching
    # =====================================================
    skl_scores = compute_single_skill_scores(
        skills,
        current_title,
        df[job_title_col].values,
        df["Target_Job_Description"].values
    )

    # =====================================================
    # حساب النتائج النهائية
    # =====================================================
    final_scores = np.zeros_like(
        sem_similarities
    )

    for j in range(len(df)):

        sem = sem_similarities[j]

        skl = skl_scores[j]

        # حساب Experience Score
        exp = calculate_experience_score(
            experience_years,
            df.loc[
                j,
                "Target_Job_Description"
            ]
        )

        # Final Score: Skills 65% + Semantic 20% + Experience 15%
        # (نفس منطق combine_final_score المستخدم بخدمة الـ AI)
        final_scores[j] = combine_final_score(skl, sem, exp)

    # =====================================================
    # ترتيب الوظائف
    # =====================================================
    sorted_indices = np.argsort(
        final_scores
    )[::-1]

    selected_jobs = []

    seen_titles = set()

    for idx in sorted_indices:

        title = df.loc[
            idx,
            job_title_col
        ]

        # تجاهل العناوين غير الصالحة
        if (
            not title
            or title.lower() in [
                "nan",
                "none",
                "null",
                "n/a",
                "unknown title"
            ]
            or title in seen_titles
        ):
            continue

        seen_titles.add(title)

        score = (
            final_scores[idx]
            * 100
        )

        selected_jobs.append(
            (title, score)
        )

        if len(selected_jobs) == top_k:
            break

    # =====================================================
    # عرض النتائج
    # =====================================================
    print(
        "\n"
        + "=" * 60
    )

    print(
        "             Manual Test Recommendation Results"
    )

    print(
        "=" * 60
    )

    print(
        f"Input Skills        : {skills}"
    )

    print(
        f"Current Job Title   : {current_title}"
    )

    print(
        f"Field of Study      : {field_of_study}"
    )

    print(
        f"Previous Job Titles : {previous_jobs}"
    )

    print(
        f"Education Level     : {education_level}"
    )

    print(
        f"Degrees             : {degrees}"
    )

    print(
        f"Experience Years    : {experience_years}"
    )

    print(
        f"Certifications      : {certifications}"
    )

    print("-" * 60)

    print(
        "Top Recommended Jobs:"
    )

    for rank, (
        title,
        score
    ) in enumerate(
        selected_jobs,
        1
    ):

        print(
            f"  {rank}. "
            f"{title:<32} "
            f"-> Match Score: "
            f"{score:.2f}%"
        )

    print(
        "=" * 60
        + "\n"
    )


# =========================================================
# 7. الاختبار اليدوي
# =========================================================
if __name__ == "__main__":

    # =====================================================
    # تجربة 1
    # =====================================================
    recommend_jobs_for_candidate(

        skills="Natural Language Processing",

        current_title="AI Engineer",

        field_of_study="Computer Science",

        previous_jobs="Software Developer",

        education_level="Bachelor",

        degrees="Computer Science",

        experience_years="3",

        certifications="Machine Learning Certificate"
    )

    # =====================================================
    # تجربة 2
    # =====================================================
    recommend_jobs_for_candidate(

        skills="Docker, Linux, AWS, Kubernetes",

        current_title="System Administrator",

        field_of_study="Information Technology",

        previous_jobs="IT Support",

        education_level="Bachelor",

        degrees="Information Technology",

        experience_years="4",

        certifications="AWS Certified"
    )