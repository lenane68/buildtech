import os
import tensorflow as tf
from PIL import Image
import pandas as pd
from sklearn.model_selection import train_test_split

# Mapping class indices to class names
PPE_CLASS_MAP = {
    0: 'Hardhat',
    1: 'Mask',
    2: 'NO-Hardhat',
    3: 'NO-Mask',
    4: 'NO-Safety Vest',
    5: 'Person',
    6: 'Safety Cone',
    7: 'Safety Vest',
    8: 'machinery',
    9: 'vehicle'
}

def create_tf_example(image_path, annotations):
    img = Image.open(image_path)
    img_width, img_height = img.size
    filename = image_path.encode('utf8')
    image_format = b'jpg'
    
    xmins = []
    xmaxs = []
    ymins = []
    ymaxs = []
    classes_text = []
    classes = []

    for ann in annotations:
        x_min, y_min, x_max, y_max, class_id = ann
        
        # Print the current annotation for debugging
        print(f"Annotation: {ann}")
        
        try:
            class_id = int(round(class_id))  # Ensure class_id is an integer and round if needed
        except ValueError:
            print(f"Invalid class_id {class_id} in annotation {ann}")
            continue  # Skip this annotation if class_id is invalid
        
        if class_id not in PPE_CLASS_MAP:
            print(f"Class ID {class_id} is not in PPE_CLASS_MAP")
            continue  # Skip this annotation if class_id is not in the map
        
        xmins.append(x_min / img_width)
        xmaxs.append(x_max / img_width)
        ymins.append(y_min / img_height)
        ymaxs.append(y_max / img_height)
        classes_text.append(PPE_CLASS_MAP[class_id].encode('utf8'))
        classes.append(class_id)
    
    tf_example = tf.train.Example(features=tf.train.Features(feature={
        'image/height': tf.train.Feature(int64_list=tf.train.Int64List(value=[img_height])),
        'image/width': tf.train.Feature(int64_list=tf.train.Int64List(value=[img_width])),
        'image/filename': tf.train.Feature(bytes_list=tf.train.BytesList(value=[filename])),
        'image/source_id': tf.train.Feature(bytes_list=tf.train.BytesList(value=[filename])),
        'image/encoded': tf.train.Feature(bytes_list=tf.train.BytesList(value=[img.tobytes()])),
        'image/format': tf.train.Feature(bytes_list=tf.train.BytesList(value=[image_format])),
        'image/object/bbox/xmin': tf.train.Feature(float_list=tf.train.FloatList(value=xmins)),
        'image/object/bbox/xmax': tf.train.Feature(float_list=tf.train.FloatList(value=xmaxs)),
        'image/object/bbox/ymin': tf.train.Feature(float_list=tf.train.FloatList(value=ymins)),
        'image/object/bbox/ymax': tf.train.Feature(float_list=tf.train.FloatList(value=ymaxs)),
        'image/object/class/text': tf.train.Feature(bytes_list=tf.train.BytesList(value=classes_text)),
        'image/object/class/label': tf.train.Feature(int64_list=tf.train.Int64List(value=classes)),
    }))
    
    return tf_example

def main():
    dataset_dir = 'css-data'
    annotations_dir = os.path.join(dataset_dir, 'train/labels')
    images_dir = os.path.join(dataset_dir, 'train/images')

    annotations = []
    for label_file in os.listdir(annotations_dir):
        image_file = label_file.replace('.txt', '.jpg')
        image_path = os.path.join(images_dir, image_file)
        label_path = os.path.join(annotations_dir, label_file)

        with open(label_path, 'r') as f:
            lines = f.readlines()
            image_annotations = [list(map(float, line.strip().split())) for line in lines]
            annotations.append((image_path, image_annotations))

    # Print some annotations for debugging
    print(f"Sample annotations: {annotations[:5]}")
    
    annotations_df = pd.DataFrame(annotations, columns=['image_path', 'annotations'])
    train_df, val_df = train_test_split(annotations_df, test_size=0.2)

    # Create TFRecord files
    with tf.io.TFRecordWriter('train.record') as writer:
        for idx, row in train_df.iterrows():
            image_path = row['image_path']
            annotations = row['annotations']
            tf_example = create_tf_example(image_path, annotations)
            if tf_example:  # Only write valid tf_examples
                writer.write(tf_example.SerializeToString())
    
    with tf.io.TFRecordWriter('val.record') as writer:
        for idx, row in val_df.iterrows():
            image_path = row['image_path']
            annotations = row['annotations']
            tf_example = create_tf_example(image_path, annotations)
            if tf_example:  # Only write valid tf_examples
                writer.write(tf_example.SerializeToString())

if __name__ == '__main__':
    main()
