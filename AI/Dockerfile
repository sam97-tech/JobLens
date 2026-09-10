FROM python:3.11-slim

WORKDIR /app

COPY requirements.txt .
RUN pip install --no-cache-dir -r requirements.txt

# تحميل نموذج التضمين وقت البناء، حتى لا تحتاج الحاوية أي اتصال
# إنترنت وقت التشغيل (تبقى على شبكة داخلية معزولة مع قاعدة البيانات)
RUN python -c "from sentence_transformers import SentenceTransformer; SentenceTransformer('all-MiniLM-L6-v2')"

COPY matching_engine.py service.py ./

EXPOSE 8500

CMD ["uvicorn", "service:app", "--host", "0.0.0.0", "--port", "8500"]
