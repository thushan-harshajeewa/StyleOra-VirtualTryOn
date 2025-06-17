from flask import Flask, request, jsonify, send_file
from flask_cors import CORS
from gradio_client import Client, file
import os

# Initialize Flask app
app = Flask(__name__)
CORS(app)  # Enable CORS for cross-origin requests

# Initialize the Gradio Client
client = Client("Nymbo/Virtual-Try-On")

# Define API route for image processing
@app.route('/process', methods=['POST'])
def process_images():
    try:
        # Get images from the request
        human_image = request.files['human_image']
        garment_image = request.files['garment_image']
        
        # Save the images locally
        human_image_path = "human_image.jpg"
        garment_image_path = "garment_image.jpg"
        human_image.save(human_image_path)
        garment_image.save(garment_image_path)

        # Define parameters
        garment_description = request.form.get('garment_description', 'A stylish outfit')
        denoise_steps = int(request.form.get('denoise_steps', 20))
        seed = int(request.form.get('seed', 20))

        # Query the Gradio API
        result = client.predict(
            dict={"background": file(human_image_path), "layers": [], "composite": None},
            garm_img=file(garment_image_path),
            garment_des=garment_description,
            is_checked=True,
            is_checked_crop=False,
            denoise_steps=denoise_steps,
            seed=seed,
            api_name="/tryon"
        )

        # Unpack results
        output_image_path, masked_image_path = result

        # Send the output image back as a response
        return send_file(output_image_path, mimetype='image/jpeg')

    except Exception as e:
        return jsonify({'error': str(e)}), 500

# Run the Flask app
if __name__ == '__main__':
    app.run(host='0.0.0.0', port=4000)
