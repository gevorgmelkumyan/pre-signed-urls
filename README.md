![Logo](https://miro.medium.com/v2/resize:fit:700/1*PZydI5Je0d9GB60H4caBEw.png)
# Laravel + Vue.js AWS S3 Pre-Signed URLs

This project demonstrates the integration of AWS S3 pre-signed URLs with a Laravel backend and Vue.js frontend. This setup allows secure, temporary access to S3 resources directly from the client-side, optimizing performance and security.

## Prerequisites

Before you begin, ensure you have the following installed:
- Docker
- Make

## Installation

1. Clone the repository:

```bash
git clone https://github.com/gevorgmelkumyan/pre-signed-urls.git
```

2. Go to the project's docker directory:
```bash
cd pre-signed-urls/docker
```

3. Run the build command:
```bash
make run
```

4. Make sure the docker environment has built successfully by visiting [http://localhost:8085](http://localhost:8085).
5. Connect to the server container (`su_server`) and run the `dev` script:
```bash
docker exec -it su_server bash
npm run dev
```
6. For the AWS S3 mock URLs to be working on your local machine, make sure to add the following line to your `/etc/hosts` file:
```bash
127.0.0.1 su_s3
```
