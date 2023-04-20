User management system using Symfony
-

# Installation

It would be quite easy with docker.

#### First, build and run services in the docker
```bash
docker-compose build
docker-compose up -d
```

#### Then you have to prepare database

```bash
docker-compose exec php bin/console doctrine:migrations:migrate
```

#### Create admin user

```bash
docker-compose exec php bin/console app:create-admin
```

#### or you could your awesome name for this user

```bash
docker-compose exec php bin/console app:create-admin --name alex
```

#### You will obtain the access token for API editing operations:

```bash
User 1 admin
Token brE94QXUom22FDuPj_qwHwuyNXbtlsHscOMlDUYSP0s
```

## Of course, you could do it without docker

You have to create a database locally and `DATABASE_URL` in the `.env` file.
Then make all commands above on the host machine:  

```bash
composer install
php bin/console doctrine:migrations:migrate
php bin/console app:create-admin
symfony serve --no-tls --port=8000 --allow-http
```

#### Great! You a ready to add/delete everything

# API
The short description of API methods.
All POST, PUT and DELETE methods are secured by access token authorization.
Moreover, only user from group `admin` can use them.
To authorize http request you should add the following header:

```bash
Authorization: Bearer <YOUR_TOKEN>
```

## Users

### Get a User

- URL: /users/{id}
- Method: GET
- URL Parameters:
- id (integer) - The ID of the user.
- Response:
  - Success:
      - id (int) - id
      - name (string) - name
      - groups (array) - array of groups
  - Error: JSON object containing an error message.

### Create a User

- URL: /users
- Method: POST
- Request Parameters:
  - name (string) - The user's name.
Response:
  Success: 
    - user
      - id (int) - id
      - name (string) - name
      - groups (array) - array of groups
    - token (string) - access token for API
  Error: JSON object containing an error message.

### Delete a User

- URL: /users/{id}
- Method: DELETE
- URL Parameters:
  - id (integer) - The ID of the user.
- Response:
  - Success: JSON object containing a success message.
  - Error: JSON object containing an error message.
  
## Groups

### Get a list of Groups

- URL: /groups
- Method: GET
- Response:
    - Success:
      - array of Groups:
          - id (int) - ID
          - name (string) - name
          - users (array) - array of users
    - Error: JSON object containing an error message.

### Get a Group

- URL: /groups/{id}
- Method: GET
- Response:
  - Success:
    - id (int) - ID
    - name (string) - name
    - users (array) - array of users
- Error: JSON object containing an error message.

### Create a Group

- URL: /groups
- Method: POST
- Request Parameters:
  - name (string) - The name of the group.
- Response:
  - Success:
    - id (int) - ID
    - name (string) - name
    - users (array) - array of users
  - Error: JSON object containing an error message.
  
### Delete a Group

- URL: /groups/{id}
- Method: DELETE
- URL Parameters:
  - id (integer) - The ID of the group.
- Response:
  - Success: JSON object containing a success message.
  -Error: JSON object containing an error message.

### Assign a User to a Group

- URL: /groups/{groupId}/users/{userId}
- Method: PUT
- URL Parameters:
  - groupId (integer) - The ID of the group.
  - userId (integer) - The ID of the user.
  - Response:
    - Success: JSON object containing a success message.
    - Error: JSON object containing an error message.

### Remove a User from a Group

- URL: /groups/{groupId}/users/{userId}
- Method: DELETE
- URL Parameters:
  - groupId (integer) - The ID of the group.
    - userId (integer) - The ID of the user.
- Response:
  - Success: JSON object containing a success message.
  - Error: JSON object containing an error message.


# TODO

- Add more tests
- Catch all errors
- Implement oAuth with access and refresh tokens
- Add logging
