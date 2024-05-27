#!/bin/bash

SERVER_ENV_FILE="../.env"
DOCKER_ENV_FILE=".env"

if [ ! -f "$DOCKER_ENV_FILE" ]; then
    echo "docker/.env is missing, copying from docker/.env.example..."
    cp .env.example .env
fi

if [ ! -f "$SERVER_ENV_FILE" ]; then
    echo "application .env is missing, copying from .env.example..."
    cp ../.env.example ../.env

    # Load environment variables from .env to this script's session
    set -a
    [ -f .env ] && . ./.env
    [ -f ../.env ] && . ../.env
    set +a

    if [ -z "$APP_URL" ]; then
        APP_URL="http://localhost:$SERVER_PORT"
    fi

    # Export all the necessary variables fetched from both .envs to the application .env
    {
        printf "\n"
        echo "APP_URL=$APP_URL"
        printf "\n"
        echo "DB_CONNECTION=mysql"
        echo "DB_HOST=su_mysql"
        echo "DB_PORT=3306"
        echo "DB_DATABASE=$DB_DATABASE"
        echo "DB_USERNAME=$DB_USERNAME"
        echo "DB_PASSWORD=$DB_PASSWORD"
        printf "\n"
        echo "AWS_ACCESS_KEY_ID=fakeAccessKey"
        echo "AWS_SECRET_ACCESS_KEY=fakeSecretKey"
        echo "AWS_DEFAULT_REGION=us-east-1"
        echo "AWS_BUCKET=su"
        echo "AWS_URL=http://su_s3:$S3_PORT"
        echo "AWS_ENDPOINT=http://su_s3:$S3_PORT"
        echo "AWS_USE_PATH_STYLE_ENDPOINT=true"
    } >> ../.env
fi
