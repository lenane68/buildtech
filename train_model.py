from ultralytics import YOLO
model = YOLO('runs/detect/safety_model322/weights/best.pt')
model.val()
