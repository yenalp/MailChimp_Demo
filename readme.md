# MailChimp V3 Demo

Using Laravel Lumen 5.4 to connect to Mailchimp V3 Api.
There is only 3 calls to the api "create list", "add member to list" and "update member in list"

## Setup

This api has been built using postgres db. 
One table is created "user" - used only to demonstrate basic auth.

## Api calls 

### Login

/api/v1/user/login - GET

Headers:

X-Auth-SITE-ID:ADMIN

Body:
```shell
{
	"password" : "password",
	"username": "admin"
}
```
### Get Lists 
/api/v1/mailchimp/list - GET

Headers:

X-Auth-id-ADMIN:1

X-Auth-SITE-ID:ADMIN

X-Auth-Token-ADMIN:bZS9bHxXMi6I0dXx7841xlNxD3eeHABO7kaQuBsazie5KdxeUpBkWSMAPDC1kh

Content-Type:application/json

Body:

### Create List 

/api/v1/mailchimp/list/create - POST

Headers:

X-Auth-id-ADMIN:1
X-Auth-SITE-ID:ADMIN
X-Auth-Token-ADMIN:bZS9bHxXMi6I0dXx7841xlNxD3eeHABO7kaQuBsazie5KdxeUpBkWSMAPDC1kh
Content-Type:application/json

Body:

{
  "permission_reminder" : "You're receiving this email because you signed up for updates about Freddie's newest hats.",
  "email_type_option" : true,
  "name" : "Paul Test",
  "campaign_defaults" : {
    "from_email" : "freddie@freddiehats.com",
    "language" : "en",
    "subject" : "",
    "from_name" : "Freddie"
  },
  "contact" : {
    "phone" : "",
    "city" : "Atlanta",
    "address1" : "675 Ponce De Leon Ave NE",
    "country" : "US",
    "company" : "MailChimp",
    "zip" : "30308",
    "state" : "GA",
    "address2" : "Suite 5000"
  }
}


### Add Member to List 

/api/v1/mailchimp/list/[list_id]/member/create - POST

Headers:

X-Auth-id-ADMIN:1
X-Auth-SITE-ID:ADMIN
X-Auth-Token-ADMIN:bZS9bHxXMi6I0dXx7841xlNxD3eeHABO7kaQuBsazie5KdxeUpBkWSMAPDC1kh
Content-Type:application/json

Body:

{
  "status" : "subscribed",
  "email_address" : "paul@paullaney.com.au",
  "merge_fields": {
    "FNAME": "Paul",
    "LNAME": "Laney"
  }
}


### Update Member in a list 

/api/v1/mailchimp/list/[list_id]/member/update/[member_id] - PATCH

Headers:

X-Auth-id-ADMIN:1
X-Auth-SITE-ID:ADMIN
X-Auth-Token-ADMIN:bZS9bHxXMi6I0dXx7841xlNxD3eeHABO7kaQuBsazie5KdxeUpBkWSMAPDC1kh
Content-Type:application/json

Body:

{
  "merge_fields": {
    "FNAME": "New",
    "LNAME": "Laney"
  }
}
