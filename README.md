# line_message_demo
- LINE Messaging APIを使ったデモ


- How to run web service
````
1. cp api-server/.env.example api-server/.env

2. modify env
LINE_MESSAGE_CHANNEL_ID=
LINE_MESSAGE_CHANNEL_SECRET=
LINE_MESSAGE_CHANNEL_TOKEN=
DEMO_LINE_USER_ID=

3. composer install
4. php artisan serve
6. ngrok http 8000

````
