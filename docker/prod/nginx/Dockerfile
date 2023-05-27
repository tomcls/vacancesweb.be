FROM nginx:alpine

#COPY srv/nginx.conf /etc/nginx/nginx.conf

COPY ./nginx.conf /etc/nginx/nginx.conf
WORKDIR /usr/share/nginx/html

RUN apt-get update && apt-get install -y -g npm
RUN npm install 
RUN npm run build

