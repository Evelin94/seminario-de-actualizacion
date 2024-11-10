import cv2
import numpy as np


face_cascade = cv2.CascadeClassifier(cv2.data.haarcascades + 'haarcascade_frontalface_default.xml')


imagenes = ["tefy.jpg","captura_reconocimiento_facial.png"]  
labels = [1] * len(imagenes) 


model = cv2.face.LBPHFaceRecognizer_create()


imagenes_array = []
for img in imagenes:
    imagen = cv2.imread(img, cv2.IMREAD_GRAYSCALE)
    imagenes_array.append(imagen)

model.train(imagenes_array, np.array(labels))


cap = cv2.VideoCapture(0)

while True:
    ret, frame = cap.read()
    gray_frame = cv2.cvtColor(frame, cv2.COLOR_BGR2GRAY)

    
    caras = face_cascade.detectMultiScale(gray_frame, scaleFactor=1.1, minNeighbors=5)

    for (x, y, w, h) in caras:
        cara_detectada = gray_frame[y:y+h, x:x+w]

       
        label, confidence = model.predict(cara_detectada)

        if label == 1 and confidence < 100:  
            nombre = "¡Reconocido!"
        else:
            nombre = "Desconocido"

        cv2.rectangle(frame, (x, y), (x+w, y+h), (0, 255, 0), 2)
        cv2.putText(frame, nombre, (x, y - 10), cv2.FONT_HERSHEY_SIMPLEX, 0.5, (255, 0, 0), 2)

    cv2.imshow('Reconocimiento Facial', frame)

    if cv2.waitKey(1) & 0xFF == ord('q'):
        break

cap.release()
cv2.destroyAllWindows()
