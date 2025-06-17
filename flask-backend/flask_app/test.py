from flask import Flask, request, jsonify, send_file, url_for
from flask_cors import CORS
from gradio_client import Client, file
import os

# Initialize Flask app
app = Flask(__name__)
CORS(app)  # Enable CORS for cross-origin requests
OUTPUT_DIR = "out_images"
os.makedirs(OUTPUT_DIR, exist_ok=True)