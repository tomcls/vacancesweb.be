FROM nginx:alpine


COPY ./docker/prod/nginx/nginx.conf /etc/nginx/nginx.conf
WORKDIR /usr/share/nginx/html

RUN apk add --update nodejs nodejs-npm
RUN npm install 
RUN npm run build

