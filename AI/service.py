from typing import List, Optional

from fastapi import FastAPI
from pydantic import BaseModel
from sentence_transformers import SentenceTransformer

from matching_engine import score_candidates_for_job, score_jobs_for_candidate

app = FastAPI(title="JobLens AI Matching Service")

print("Loading Sentence Transformer model (all-MiniLM-L6-v2)...")
model = SentenceTransformer("all-MiniLM-L6-v2")
print("Model loaded. Matching service ready.")


class Candidate(BaseModel):
    skills: str = ""
    education: str = ""
    experience_years: str = ""
    about: str = ""
    current_title: str = ""


class Job(BaseModel):
    id: int
    title: str
    description: str = ""
    requirements: Optional[str] = ""
    skills: List[str] = []


class MatchRequest(BaseModel):
    candidate: Candidate
    jobs: List[Job]


class MatchResult(BaseModel):
    job_id: int
    score: float


class MatchResponse(BaseModel):
    results: List[MatchResult]


class CandidateWithId(BaseModel):
    id: int
    skills: str = ""
    education: str = ""
    experience_years: str = ""
    about: str = ""
    current_title: str = ""


class MatchCandidatesRequest(BaseModel):
    job: Job
    candidates: List[CandidateWithId]


class CandidateMatchResult(BaseModel):
    candidate_id: int
    score: float


class MatchCandidatesResponse(BaseModel):
    results: List[CandidateMatchResult]


@app.get("/health")
def health():
    return {"status": "ok"}


@app.post("/match", response_model=MatchResponse)
def match(payload: MatchRequest):
    data = payload.dict()
    results = score_jobs_for_candidate(model, data["candidate"], data["jobs"])
    return {"results": results}


@app.post("/match-candidates", response_model=MatchCandidatesResponse)
def match_candidates(payload: MatchCandidatesRequest):
    data = payload.dict()
    results = score_candidates_for_job(model, data["job"], data["candidates"])
    return {"results": results}
