from ultralytics import YOLO

# Load the YOLOv8 model (nano version for faster training)
model = YOLO('yolov8n.pt')

# Train the model
model.train(
    data='css-data/data.yaml',  # Path to the updated data.yaml
    epochs=50,
    imgsz=640,
    batch=16,
    name='safety_model32'
)


