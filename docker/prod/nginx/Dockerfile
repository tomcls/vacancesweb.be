FROM nginx:alpine


COPY ./docker/prod/nginx/nginx.conf /etc/nginx/nginx.conf
WORKDIR /usr/share/nginx/html

RUN apk update && apt add npm
RUN npm install 
RUN npm run build

