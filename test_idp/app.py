from flask import Flask, render_template
import random

app = Flask(__name__)

@app.route('/')
def index():
    url = "https://github.com/chiroiu96/Proiect_IDP/blob/master/Diagrama.png"
    return render_template('index.html', url=url)

if __name__ == "__main__":
    app.run(host="0.0.0.0")