from flask import Flask, render_template
import random

app = Flask(__name__)

@app.route('/')
def index():
    url = "Diagrama.jpg"
    return render_template('index.html', url=url)

if __name__ == "__main__":
    app.run(host="0.0.0.0")
