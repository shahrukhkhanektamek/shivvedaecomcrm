import sys
import os

# force Python to look into models location
sys.path.append(os.path.expanduser("~") + r"\AppData\Roaming\Python\Python313\site-packages")

import face_recognition_models
import face_recognition

print("✅ Face Recognition ready with models")
