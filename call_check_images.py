import time
import requests

# URL of the PHP page you want to call (adjust the path as needed)
php_url = "http://localhost/buildtech/check_images.php"

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

    # Wait for 10 minutes before calling the PHP page again
    time.sleep(600)  # 600 seconds = 10 minutes
