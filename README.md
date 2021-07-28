# Really simple webhooks
<b>What is it?</b>  
-- Really simple webhooks builder. Once your webhook is triggered it will run some kind command (or shell script) on your server

![Really-simple-webhooks-readme-image](readme.gif)

# Quick startup
`$ git clone https://github.com/kasteckis/Really-simple-webhooks.git`  
`$ cd ./Really-simple-webhooks`  
`$ docker-compose up`  
`$ docker exec -it trigger_web bash`  
`$ bin/console app:create-user admin@admin.dev password`  
`Visit 192.168.4.2 and login`  

Or run:  
`$ ./tools/build.sh`

# Deploying on production server

* Clone repository and upload it to your desired folder and write:  
`$ touch .env.local`  
* Open up .env.local file and write the following:  
``DATABASE_URL=mysql://DB_USER:DB_PASSWORD@localhost:3306/DB_NAME``  
``APP_ENV=prod``  
* Then build your project by writing the following:  
`$ ./tools/build.sh`  
* Visit your website. Dont forget to create your first user by writing the following:  
`$ bin/console app:create-user admin@admin.dev password`
