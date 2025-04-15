import json
from ultralytics import YOLO
from collections import Counter
import os
import cv2
import numpy as np
import time

# Define a color palette for the classes with BGR values for OpenCV
class_colors = {
    "Hardhat": (0, 255, 0),  # Green
    "Mask": (0, 0, 255),  # Red
    "NO-Hardhat": (0, 0, 255),  # Red
    "NO-Mask": (0, 128, 255),  
    "NO-Safety Vest": (0, 165, 255),  # Orange (BGR)
    "Person": (0, 0, 255),  # Red
    "Safety Cone": (255, 0, 255),  # Purple
    "Safety Vest": (0, 255, 255),  # Yellow
    "machinery": (128, 0, 128),  # Purple
    "vehicle": (0, 128, 0)  # Dark Green
}

def start_analyze():
    time.sleep(3600)

def analyze_image(image_path):
    try:

        count_unsafe = 0
        # Load the trained YOLO model
        model = YOLO("runs/detect/safety_model322/weights/best.pt")

        # Perform inference on the image
        results = model(image_path)

        # Extract the first result
        first_result = results[0]

        class_names = [
            "Hardhat", "Mask", "NO-Hardhat", "NO-Mask", "NO-Safety Vest", 
            "Person", "Safety Cone", "Safety Vest", "machinery", "vehicle"
        ]

        # Read the image to draw on it
        image = cv2.imread(image_path)

        confidence_threshold = 0.4

        # Draw bounding boxes and label them
        for idx, box in enumerate(first_result.boxes):
            # Get coordinates of the bounding box
            x1, y1, x2, y2 = box.xyxy[0]  # coordinates of the box (x1, y1, x2, y2)
            
            # Get the class name of the detected object
            class_id = int(box.cls[0])
            class_name = class_names[class_id]

            confidence = box.conf[0].item()

            if(class_name != "Person" and class_name != "machinery" and class_name != "vehicle" and class_name != "Safety Vest" and class_name != "Mask" and class_name != "Hardhat" and confidence >= confidence_threshold):

                count_unsafe += 1
                color = class_colors.get(class_name, (255, 255, 255))  # Default to white if no color found

                # Draw rectangle around the object
                cv2.rectangle(image, (int(x1), int(y1)), (int(x2), int(y2)), color, 2)

                # Put the label (class name) text above the rectangle
                label = f"{class_name}"
                cv2.putText(image, label, (int(x1), int(y1) - 10), cv2.FONT_HERSHEY_SIMPLEX, 0.8, color, 2)


        # Define the output directory using an absolute path
        output_dir = os.path.join("img", "after_analyzing")

        if not os.path.exists(output_dir):
            os.makedirs(output_dir)

        # Prepare output image path with a unique filename
        base_filename = "with_boxes"
        extension = ".jpg"
        counter = 1
        output_image_path = os.path.join(output_dir, f"{base_filename}{counter}{extension}")

        # Ensure unique filename
        while os.path.exists(output_image_path):
            counter += 1
            output_image_path = os.path.join(output_dir, f"{base_filename}{counter}{extension}")

        # Save the image
        success = cv2.imwrite(output_image_path, image)


        if success:
            print(f"Image successfully saved at {output_image_path}")
        else:
            print(f"Failed to save image at {output_image_path}")

        # Detect the classes and count them
        detected_classes = [class_names[int(cls)] for cls in first_result.boxes.cls]

        class_counts = Counter(detected_classes)
        class_counts = dict(class_counts)

        # File name to save the JSON
        json_file_name = "class_counts.json"

        # Write the dictionary to a JSON file
        with open(json_file_name, 'w') as json_file:
            json.dump(class_counts, json_file, indent=4)

        # Return the output image path in a JSON-friendly format

        return (output_image_path,count_unsafe)

    except Exception as e:
        # Handle exceptions and return error message
        import traceback
        traceback.print_exc()
        return {"status": "error", "message": str(e)}

if __name__ == "__main__":
    import sys
    image_path = sys.argv[1]
    result = analyze_image(image_path)
    print(json.dumps(result))  # This prints the result as JSON
