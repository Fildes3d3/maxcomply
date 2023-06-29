### User

#### `POST` `/user/`

Secured: `no`

Add new user.

Sample request:

```json
{
  "username":"john doe",
  "password":"pass",
  "email":"j@dc.com"
}
```

Sample response:

```json
{
  "id": 1,
  "email": "j@dc.com",
  "roles": [
    "ROLE_USER"
  ],
  "password": "$2y$13$s9ZOo2cy8vMyNfGi2HeYae4czabHOVQOF9sgmHxxFco9y.WgzcAje",
  "username": "john doe",
  "userIdentifier": "j@dc.com"
}
```
