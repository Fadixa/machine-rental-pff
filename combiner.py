import os

# الأقسام لي بغينا نجمعوها من مشروع Laravel
TARGET_FOLDERS = [
    'app/Http/Controllers',
    'app/Models',
    'routes',
    'resources/views'
]

OUTPUT_FILE = 'project_context.txt'

with open(OUTPUT_FILE, 'w', encoding='utf-8') as outfile:
    for folder in TARGET_FOLDERS:
        if os.path.exists(folder):
            for root, dirs, files in os.walk(folder):
                for file in files:
                    # كنجمعو غير الملفات ديال الكود لي غاتستافدي منها
                    if file.endswith(('.php', '.blade.php', '.js')):
                        file_path = os.path.join(root, file)
                        outfile.write(f"\n\n{'='*40}\n")
                        outfile.write(f"FILE: {file_path}\n")
                        outfile.write(f"{='*40}\n\n")
                        try:
                            with open(file_path, 'r', encoding='utf-8') as infile:
                                outfile.write(infile.read())
                        except Exception as e:
                            outfile.write(f"[Erreur de lecture : {e}]\n")

print("تم بنجاح! كاع الكود تلم ف ملف project_context.txt")