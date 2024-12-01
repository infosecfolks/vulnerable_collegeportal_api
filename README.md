# Vulnerable_ecommerce_app
This vulnerable e-commerce application is built in a Docker environment using Apache, PHP, and MySQL technologies. It is intended solely for educational purposes to practice penetration testing. Do not attempt these techniques on real-world applications without proper consent from the application owners.

Default username & password 
----------------------------
username - ravi
password - hod@123
role - HOD (Head of the department)

username - krishna
password - abc123
role - Student

![Architecture](vulnerable_collegeportal_api.png)

Follow the below steps to deploy it as docker in your machine.

> [!TIP]
> We recommend using Kali Linux as your base OS for running these Docker containers, as it comes pre-installed with the essential tools for learning penetration testing! 

## Steps to Install and enable docker

> [!NOTE]
> If you don't have docker installted in your machine you can follow this below steps, otherwise skip to Building and running docker containers

Update kali repository <br> 
**#sudo apt-get update**

Installing docker <br> 
**#sudo apt-get install -y docker.io**

Start and enable docker service<br> 
**#sudo systemctl start docker <br> 
#sudo systemctl enable docker**

Check docker version <br> 
**#docker --version**

Download the latest Docker Compose binary<br> 
**#sudo curl -L "https://github.com/docker/compose/releases/latest/download/docker-compose-$(uname -s)-$(uname -m)" -o /usr/local/bin/docker-compose**

Apply executable permissions<br> 
**#sudo chmod +x /usr/local/bin/docker-compose**

Verify the installation<br> 
**#docker-compose --version**

## Steps to run vulnerable ecommerce as docker container 

After installing docker, now navigate to the cloned directory which is "vulnerable_ecommerce_app" and execute following docker commands <br> 
to deploy and launch vulnerable ecommerce application

This command build and run both the containers and intialize database - One single command make everything setupe in background and api will be available on 9090 port for testing <br> 
**#docker-compose up --build -d**


Follow us for more updates <br> 
[INFOSEC FOLKS - LINKEDIN](https://www.linkedin.com/company/infosecfolks-global/) <br> 
[INFOSEC FOLKS - YOUTUBE](https://www.youtube.com/@infosecfolks-global/) <br>
[INFOSEC FOLKS Telugu - YOUTUBE](https://www.youtube.com/@InfosecFolks-Telugu/) <br>

