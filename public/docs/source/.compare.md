---
title: API Reference

language_tabs:
- bash
- javascript

includes:

search: true

toc_footers:
- <a href='http://github.com/mpociot/documentarian'>Documentation Powered by Documentarian</a>
---
<!-- START_INFO -->
# Info

Welcome to the generated API reference.
[Get Postman Collection](https://prisma.care/docs/collection.json)
<!-- END_INFO -->

#Album

Controller used return, persist and remove Album data for particular Patients
Albums are collections of visual content used to stimulate discussions between families and their loved ones
<!-- START_ad4f8ad1e8c8c629d786a7a966f19150 -->
## Returns an array of Albums belonging to a particular Patient

> Example request:

```bash
curl -X GET "https://prisma.care/v1/patient/{patient}/album" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://prisma.care/v1/patient/{patient}/album",
    "method": "GET",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
{
    "meta": {
        "code": 401,
        "message": "No authorization token provided"
    },
    "response": []
}
```

### HTTP Request
`GET v1/patient/{patient}/album`

`HEAD v1/patient/{patient}/album`


<!-- END_ad4f8ad1e8c8c629d786a7a966f19150 -->

<!-- START_ea3628896d64fa0092c39d47efbd8819 -->
## Persists an Album to storage for a particular Patient

> Example request:

```bash
curl -X POST "https://prisma.care/v1/patient/{patient}/album" \
-H "Accept: application/json" \
    -d "title"="dignissimos" \

```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://prisma.care/v1/patient/{patient}/album",
    "method": "POST",
    "data": {
        "title": "dignissimos"
},
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```


### HTTP Request
`POST v1/patient/{patient}/album`

#### Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    title | string |  required  | 

<!-- END_ea3628896d64fa0092c39d47efbd8819 -->

<!-- START_b62bc4e248cfb6855fec92ae6d2e0505 -->
## Returns an Album belonging to a particular Patient

> Example request:

```bash
curl -X GET "https://prisma.care/v1/patient/{patient}/album/{album}" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://prisma.care/v1/patient/{patient}/album/{album}",
    "method": "GET",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
{
    "meta": {
        "code": 401,
        "message": "No authorization token provided"
    },
    "response": []
}
```

### HTTP Request
`GET v1/patient/{patient}/album/{album}`

`HEAD v1/patient/{patient}/album/{album}`


<!-- END_b62bc4e248cfb6855fec92ae6d2e0505 -->

<!-- START_cf9f628c9a3fd54de20c9285bb7b1103 -->
## Removes an Album from storage

> Example request:

```bash
curl -X DELETE "https://prisma.care/v1/patient/{patient}/album/{album}" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://prisma.care/v1/patient/{patient}/album/{album}",
    "method": "DELETE",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```


### HTTP Request
`DELETE v1/patient/{patient}/album/{album}`


<!-- END_cf9f628c9a3fd54de20c9285bb7b1103 -->

<!-- START_a8b1413ec25df7d870d7078a4e438ab6 -->
## Updates an Album belonging to particular Patient

> Example request:

```bash
curl -X PATCH "https://prisma.care/v1/patient/{patient}/album/{album}" \
-H "Accept: application/json" \
    -d "title"="quia" \

```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://prisma.care/v1/patient/{patient}/album/{album}",
    "method": "PATCH",
    "data": {
        "title": "quia"
},
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```


### HTTP Request
`PATCH v1/patient/{patient}/album/{album}`

#### Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    title | string |  optional  | 

<!-- END_a8b1413ec25df7d870d7078a4e438ab6 -->

#Auth
<!-- START_b4c02be43324d119a1f41245b90fb886 -->
## Authenticate a User

> Example request:

```bash
curl -X POST "https://prisma.care/v1/user/signin" \
-H "Accept: application/json" \
    -d "email"="dion.stanton@example.net" \
    -d "password"="ducimus" \

```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://prisma.care/v1/user/signin",
    "method": "POST",
    "data": {
        "email": "dion.stanton@example.net",
        "password": "ducimus"
},
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```


### HTTP Request
`POST v1/user/signin`

#### Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    email | email |  required  | 
    password | string |  required  | 

<!-- END_b4c02be43324d119a1f41245b90fb886 -->

<!-- START_c888faa7c251178b85f34a01da2c642d -->
## Log out a User

> Example request:

```bash
curl -X POST "https://prisma.care/v1/user/signout" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://prisma.care/v1/user/signout",
    "method": "POST",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```


### HTTP Request
`POST v1/user/signout`


<!-- END_c888faa7c251178b85f34a01da2c642d -->

#Connection

Controller used to connect Users to Patients
<!-- START_6e256bae3fca78799fe77d79ab63c8fc -->
## Connect a user with a patient.

> Example request:

```bash
curl -X LINK "https://prisma.care/v1/patient/{patientId}/connection" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://prisma.care/v1/patient/{patientId}/connection",
    "method": "LINK",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```


### HTTP Request
`LINK v1/patient/{patientId}/connection`


<!-- END_6e256bae3fca78799fe77d79ab63c8fc -->

<!-- START_a4405aee6b8c2ed72752c3b501e8f3ec -->
## Disconnect a user from a patient.

> Example request:

```bash
curl -X UNLINK "https://prisma.care/v1/patient/{patientId}/connection" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://prisma.care/v1/patient/{patientId}/connection",
    "method": "UNLINK",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```


### HTTP Request
`UNLINK v1/patient/{patientId}/connection`


<!-- END_a4405aee6b8c2ed72752c3b501e8f3ec -->

#Heritage\DefaultAlbum

When a Patient is created, a default set of Heritage Albums are generated and assigned to that Patient
This ensures that there is content available for the new User/Patient
Because the generated Heritage Albums are assigned to the Patient the can also be deleted
<!-- START_5f9ea1380d5ddf373f9a97d44e4b44ad -->
## Fetch Heritage Albums

> Example request:

```bash
curl -X GET "https://prisma.care/v1/album" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://prisma.care/v1/album",
    "method": "GET",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
{
    "meta": {
        "code": 401,
        "message": "No authorization token provided"
    },
    "response": []
}
```

### HTTP Request
`GET v1/album`

`HEAD v1/album`


<!-- END_5f9ea1380d5ddf373f9a97d44e4b44ad -->

<!-- START_f75eb541a44d3e3ef0ac50630a10161d -->
## Persist a new Heritage Album

> Example request:

```bash
curl -X POST "https://prisma.care/v1/album" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://prisma.care/v1/album",
    "method": "POST",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```


### HTTP Request
`POST v1/album`


<!-- END_f75eb541a44d3e3ef0ac50630a10161d -->

<!-- START_f835437eacfa197096feeee8fc97b8ae -->
## Fetch a single Heritage Album

> Example request:

```bash
curl -X GET "https://prisma.care/v1/album/{album}" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://prisma.care/v1/album/{album}",
    "method": "GET",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
{
    "meta": {
        "code": 401,
        "message": "No authorization token provided"
    },
    "response": []
}
```

### HTTP Request
`GET v1/album/{album}`

`HEAD v1/album/{album}`


<!-- END_f835437eacfa197096feeee8fc97b8ae -->

<!-- START_155124900ddc87064befc8014e974b66 -->
## Remove a specific DefaultAlbum

> Example request:

```bash
curl -X DELETE "https://prisma.care/v1/album/{album}" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://prisma.care/v1/album/{album}",
    "method": "DELETE",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```


### HTTP Request
`DELETE v1/album/{album}`


<!-- END_155124900ddc87064befc8014e974b66 -->

<!-- START_fd29650ce9bc620f6991745a7b9528ce -->
## Update a specific DefaultAlbum

> Example request:

```bash
curl -X PATCH "https://prisma.care/v1/album/{album}" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://prisma.care/v1/album/{album}",
    "method": "PATCH",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```


### HTTP Request
`PATCH v1/album/{album}`


<!-- END_fd29650ce9bc620f6991745a7b9528ce -->

#Heritage\Heritage

A Heritage is a Story supplied by default to all Users
and usually supplied by a Heritage organisation
<!-- START_ec5c25310511311fdd00db15636bc28e -->
## Fetch all Heritages for an Album

> Example request:

```bash
curl -X GET "https://prisma.care/v1/album/{album}/heritage" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://prisma.care/v1/album/{album}/heritage",
    "method": "GET",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
{
    "meta": {
        "code": 401,
        "message": "No authorization token provided"
    },
    "response": []
}
```

### HTTP Request
`GET v1/album/{album}/heritage`

`HEAD v1/album/{album}/heritage`


<!-- END_ec5c25310511311fdd00db15636bc28e -->

<!-- START_dc56bc78f254d14fa22600cf20d3cf5b -->
## Persist a new Heritage and assign it to an Album

> Example request:

```bash
curl -X POST "https://prisma.care/v1/album/{album}/heritage" \
-H "Accept: application/json" \
    -d "description"="asperiores" \

```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://prisma.care/v1/album/{album}/heritage",
    "method": "POST",
    "data": {
        "description": "asperiores"
},
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```


### HTTP Request
`POST v1/album/{album}/heritage`

#### Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    description | string |  required  | 

<!-- END_dc56bc78f254d14fa22600cf20d3cf5b -->

<!-- START_d31f1a81cf2488f528918ccede73b714 -->
## Fetch a specific Heritage

These are attached to Albums and therefore are User specific

> Example request:

```bash
curl -X GET "https://prisma.care/v1/album/{album}/heritage/{heritage}" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://prisma.care/v1/album/{album}/heritage/{heritage}",
    "method": "GET",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
{
    "meta": {
        "code": 401,
        "message": "No authorization token provided"
    },
    "response": []
}
```

### HTTP Request
`GET v1/album/{album}/heritage/{heritage}`

`HEAD v1/album/{album}/heritage/{heritage}`


<!-- END_d31f1a81cf2488f528918ccede73b714 -->

<!-- START_44f76c2d1f206a43db362787659192df -->
## Remove the specified heritage

> Example request:

```bash
curl -X DELETE "https://prisma.care/v1/album/{album}/heritage/{heritage}" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://prisma.care/v1/album/{album}/heritage/{heritage}",
    "method": "DELETE",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```


### HTTP Request
`DELETE v1/album/{album}/heritage/{heritage}`


<!-- END_44f76c2d1f206a43db362787659192df -->

<!-- START_24bcf6aa284f5c965666ef2ad9f0b8c7 -->
## Update a specific Heritage

> Example request:

```bash
curl -X PATCH "https://prisma.care/v1/album/{album}/heritage/{heritage}" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://prisma.care/v1/album/{album}/heritage/{heritage}",
    "method": "PATCH",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```


### HTTP Request
`PATCH v1/album/{album}/heritage/{heritage}`


<!-- END_24bcf6aa284f5c965666ef2ad9f0b8c7 -->

#Heritage\HeritageAsset

HeritageAssets are photographic and video materials used to stimulate discussion between the User and Patient
HeritageAssets are supplied by local heritage organisations rather than from the Users
<!-- START_550d34127fc25491d03f7a3280ffdfe2 -->
## Store a new HeritageAsset and attach it to a Heritage

HeritageAssets can be photos or URLs to videos on external services such as YouTube or Vimeo

> Example request:

```bash
curl -X POST "https://prisma.care/v1/album/{album}/heritage/{heritage}/asset" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://prisma.care/v1/album/{album}/heritage/{heritage}/asset",
    "method": "POST",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```


### HTTP Request
`POST v1/album/{album}/heritage/{heritage}/asset`


<!-- END_550d34127fc25491d03f7a3280ffdfe2 -->

<!-- START_d3f782ccc657995e6624c7cb86d81fa5 -->
## Fetch a particular HeritageAsset

> Example request:

```bash
curl -X GET "https://prisma.care/v1/album/{album}/heritage/{heritage}/asset/{asset}" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://prisma.care/v1/album/{album}/heritage/{heritage}/asset/{asset}",
    "method": "GET",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
{
    "meta": {
        "code": 401,
        "message": "No authorization token provided"
    },
    "response": []
}
```

### HTTP Request
`GET v1/album/{album}/heritage/{heritage}/asset/{asset}`

`HEAD v1/album/{album}/heritage/{heritage}/asset/{asset}`


<!-- END_d3f782ccc657995e6624c7cb86d81fa5 -->

#Invite\User

When a User invites another user to connect to a Patient,
a temporary user is created if the User does not already exist.

In this case an email invite is sent to the new User with a tokenized URL
They can use this to access a password (re)set page and accep their membership of Prisma
<!-- START_88c0a7ad77d75f98eed8c7a8e1280339 -->
## Persist New Invited User

Checks for the existence of an invited User
Creates then if they don't already exist and then generates a token and sends an email invite

> Example request:

```bash
curl -X POST "https://prisma.care/v1/invite" \
-H "Accept: application/json" \
    -d "email"="graham.stacy@example.com" \
    -d "firstName"="et" \
    -d "lastName"="et" \
    -d "patientId"="286" \
    -d "inviterId"="286" \

```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://prisma.care/v1/invite",
    "method": "POST",
    "data": {
        "email": "graham.stacy@example.com",
        "firstName": "et",
        "lastName": "et",
        "patientId": 286,
        "inviterId": 286
},
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```


### HTTP Request
`POST v1/invite`

#### Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    email | email |  required  | 
    firstName | string |  required  | 
    lastName | string |  required  | 
    patientId | integer |  required  | 
    inviterId | integer |  required  | 

<!-- END_88c0a7ad77d75f98eed8c7a8e1280339 -->

#Patient

A Patient is a person who has dementia and receives nursing care.
Patients are the loved ones of family members (Users)

This Controller allows new Patients to be created and a Patient request returned for a particular Patient
<!-- START_066cb05849e8763b019064a0e07d9e18 -->
## Persist a new Patient to storage

> Example request:

```bash
curl -X POST "https://prisma.care/v1/patient" \
-H "Accept: application/json" \
    -d "firstName"="inventore" \
    -d "lastName"="inventore" \

```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://prisma.care/v1/patient",
    "method": "POST",
    "data": {
        "firstName": "inventore",
        "lastName": "inventore"
},
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```


### HTTP Request
`POST v1/patient`

#### Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    firstName | string |  required  | 
    lastName | string |  required  | 

<!-- END_066cb05849e8763b019064a0e07d9e18 -->

<!-- START_b038860a6de892bd1762c093acde583f -->
## Return the specified Patient if permitted by the Form Request

> Example request:

```bash
curl -X GET "https://prisma.care/v1/patient/{patient}" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://prisma.care/v1/patient/{patient}",
    "method": "GET",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
{
    "meta": {
        "code": 401,
        "message": "No authorization token provided"
    },
    "response": []
}
```

### HTTP Request
`GET v1/patient/{patient}`

`HEAD v1/patient/{patient}`


<!-- END_b038860a6de892bd1762c093acde583f -->

#Story

Stories are made up of a photo or video and a short text.
They are used to stimulate discussion between the User and Patient
Stories are collected in Albums and a number of Heritage items are supplied by default
when a new User registers a Patient
<!-- START_866bab64d6ed2769ed40d8b7d855c47d -->
## Persist a new Story

> Example request:

```bash
curl -X POST "https://prisma.care/v1/patient/{patient}/story" \
-H "Accept: application/json" \
    -d "description"="nisi" \
    -d "creatorId"="nisi" \
    -d "albumId"="nisi" \

```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://prisma.care/v1/patient/{patient}/story",
    "method": "POST",
    "data": {
        "description": "nisi",
        "creatorId": "nisi",
        "albumId": "nisi"
},
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```


### HTTP Request
`POST v1/patient/{patient}/story`

#### Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    description | string |  required  | 
    creatorId | string |  required  | 
    albumId | string |  required  | 

<!-- END_866bab64d6ed2769ed40d8b7d855c47d -->

<!-- START_5d7f9cc97a3424083f960a55c685e013 -->
## Fetch a Story

> Example request:

```bash
curl -X GET "https://prisma.care/v1/patient/{patient}/story/{story}" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://prisma.care/v1/patient/{patient}/story/{story}",
    "method": "GET",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
{
    "meta": {
        "code": 401,
        "message": "No authorization token provided"
    },
    "response": []
}
```

### HTTP Request
`GET v1/patient/{patient}/story/{story}`

`HEAD v1/patient/{patient}/story/{story}`


<!-- END_5d7f9cc97a3424083f960a55c685e013 -->

<!-- START_7ffef2ec1e02c593ac29771e7fa25016 -->
## Remove a story from storage.

> Example request:

```bash
curl -X DELETE "https://prisma.care/v1/patient/{patient}/story/{story}" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://prisma.care/v1/patient/{patient}/story/{story}",
    "method": "DELETE",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```


### HTTP Request
`DELETE v1/patient/{patient}/story/{story}`


<!-- END_7ffef2ec1e02c593ac29771e7fa25016 -->

<!-- START_dff8513cd59cd1fac508561d65f1b98d -->
## Update a Story

> Example request:

```bash
curl -X PATCH "https://prisma.care/v1/patient/{patient}/story/{story}" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://prisma.care/v1/patient/{patient}/story/{story}",
    "method": "PATCH",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```


### HTTP Request
`PATCH v1/patient/{patient}/story/{story}`


<!-- END_dff8513cd59cd1fac508561d65f1b98d -->

#StoryAsset

StoryAssets are photographic and video materials used to stimulate discussion between the User and Patient
<!-- START_2d406804fd9d330a3eba6fed7ee3993c -->
## Store a new StoryAsset and attach it to a Story

StoryAssets can be photos or URLs to videos on external services such as YouTube or Vimeo

> Example request:

```bash
curl -X POST "https://prisma.care/v1/patient/{patient}/story/{story}/asset" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://prisma.care/v1/patient/{patient}/story/{story}/asset",
    "method": "POST",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```


### HTTP Request
`POST v1/patient/{patient}/story/{story}/asset`


<!-- END_2d406804fd9d330a3eba6fed7ee3993c -->

<!-- START_9250fae4bf8496feab6a061b1818ebaf -->
## Fetch a particular StoryAsset

> Example request:

```bash
curl -X GET "https://prisma.care/v1/patient/{patient}/story/{story}/asset/{asset}" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://prisma.care/v1/patient/{patient}/story/{story}/asset/{asset}",
    "method": "GET",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
{
    "meta": {
        "code": 401,
        "message": "No authorization token provided"
    },
    "response": []
}
```

### HTTP Request
`GET v1/patient/{patient}/story/{story}/asset/{asset}`

`HEAD v1/patient/{patient}/story/{story}/asset/{asset}`


<!-- END_9250fae4bf8496feab6a061b1818ebaf -->

#User

Users are family members of a Patient
A User can be attached to multiple Patients
A User can create new Albums and Story content
A User can also invite other Users and connect them with a Patient
<!-- START_7bd54b11841f8fc6b89028e1879145bc -->
## Return a User

> Example request:

```bash
curl -X GET "https://prisma.care/v1/user" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://prisma.care/v1/user",
    "method": "GET",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
null
```

### HTTP Request
`GET v1/user`

`HEAD v1/user`


<!-- END_7bd54b11841f8fc6b89028e1879145bc -->

<!-- START_7b31d0c9ab1ff57a8c35ae26a90a16d3 -->
## Persist a New User

> Example request:

```bash
curl -X POST "https://prisma.care/v1/user" \
-H "Accept: application/json" \
    -d "email"="bbradtke@example.com" \
    -d "password"="autem" \
    -d "firstName"="autem" \
    -d "lastName"="autem" \

```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://prisma.care/v1/user",
    "method": "POST",
    "data": {
        "email": "bbradtke@example.com",
        "password": "autem",
        "firstName": "autem",
        "lastName": "autem"
},
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```


### HTTP Request
`POST v1/user`

#### Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    email | email |  required  | 
    password | string |  required  | 
    firstName | string |  required  | 
    lastName | string |  required  | 

<!-- END_7b31d0c9ab1ff57a8c35ae26a90a16d3 -->

