<b>Simple Symfony</b><br>
here's a simple dockerized backend project utilizing symfony 6 framework and postgres database<br>
after cloning, cd into project directory. Then run these commands:<br>
<code>
docker-compose build<br>
docker-compose up -d<br>
</code>
Afterward, the backend is available at <code>localhost</code> and the API docs is available at <code>localhost/api</code> </br>
Also there's an API endpoint <code>/api/car/{carId}/reviews</code> which get the latest reviews of a given car with rating above 6 stars