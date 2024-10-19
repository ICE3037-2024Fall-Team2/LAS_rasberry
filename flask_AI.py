from flask import Flask, request, jsonify
import base64
import cv2
import numpy as np
from flask_cors import CORS

app = Flask(__name__)
CORS(app)


@app.route('/upload_image', methods=['POST'])
def upload_image():
    # Get base64 encoded image
    data = request.json['image']
    image_data = base64.b64decode(data.split(',')[1])

    # Convert image to OpenCV format
    np_arr = np.frombuffer(image_data, np.uint8)
    img = cv2.imdecode(np_arr, cv2.IMREAD_COLOR)

    # Perform any processing needed here (e.g., face detection, QR detection, etc.)
    # For now, we just return 1 for simplicity
    return jsonify({'result': 1})

if __name__ == '__main__':
    app.run(debug=True)
