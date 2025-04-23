import time
import requests
import os

# Get the folder name where the script is located
project_folder_name = os.path.basename(os.path.dirname(os.path.abspath(__file__)))

# Construct the URL dynamically
php_url = f"http://localhost/{project_folder_name}/check_images.php"
# Infinite loop to keep calling the PHP page
while True:
    try:
        # Send GET request to the PHP page
        response = requests.get(php_url)

        # Check if the request was successful
        if response.status_code == 200:
            print("PHP page called successfully.")
        else:
            print(f"Failed to call PHP page. Status code: {response.status_code}")
    except requests.exceptions.RequestException as e:
        print(f"Error while calling PHP page: {e}")

    time.sleep(3600)  
