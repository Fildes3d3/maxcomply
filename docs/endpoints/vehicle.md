### Vehicle

#### `GET` `/api/v1/vehicle/maker/{type}`

Secured: `yes`

Get vehicle maker by vehicle type.

Possible values for `type`: *`electric`*, *`hybrid`*, *`internalCombustion`*

Sample response:

```json
{ 
  "make":"Tesla"
}
```

#### `GET` `/api/v1/vehicle/tech-data/{id}`

Secured: `yes`

Get technical details of a specific vehicle.

Sample response:

```json
{
  "type":"electric",
  "topSpeed":200,
  "dimensions": {
    "width":2,
    "length":1.8,
    "height":1.2
  },
  "engineData": {
    "displacement":3500,
    "power":400
  }
}
```

#### `PATCH` `/api/v1/vehicle/tech-data/{param}/update/{id}`

Secured: `yes`

Update a specific technical parameter of a vehicle.

Possible values for `param`: *`type`*, *`topSpeed`*, *`dimensions`*, *`engineData`*, 

Sample request:

```json
{
  "engineData": {
    "displacement":1200,
    "power":90
  }
}
```

Sample response:

```json
{
  "id":361,
  "make":"Lewis Bednar",
  "techData": {
    "type":"electric",
    "topSpeed":200,
    "dimensions": {
      "width":2,
      "height":1.2,
      "length":1.8
    },
    "engineData": {
      "displacement":1200,
      "power":90
    }
  }
}
```
