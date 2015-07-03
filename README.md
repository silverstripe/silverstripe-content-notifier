# SilverStripe Content Notifier Module

## Maintainer
Aaron Carlino (aaron at silverstripe dot com)

## Description

The Content Notifier module is used to allow moderation of user-generated content on websites running SilverStripe. When content is created or updated, an admin is notified and can approve the change. How strict the approval process is and who gets notified and how often is all configurable.

## Usage

* Install the repository to your SilverStripe project in the web root., e.g. ```/content-notifier/```. 
* ```/dev/build?flush```
* Add the extension ```ContentNotifierExtension``` to any DataObjects you want to use the notifier. The DataObject class receiving the extension **must implement the ContentNotifier interface**.

### Requirements
As defined in the ```ContentNotfifer``` interface, you must add the following methods to your DataObject:

* ```getContentNotifierExcerpt()```: The summary of content that will be displayed in the notification email.
* ```getContentNotifierLink()```: The link to edit this record in the CMS.
* ```getContentNotifierHeadline()```: The title of the DataObject that will appear in the notification email

#### Example
```php
<?php

class Comment extends DataObject implements ContentNotifier
{
	
	private static $db = array (
		'Comment' => 'Text',
		'Author' => 'Varchar'
	);


    public function getContentNotifierExcerpt() {
    	return $this->obj('Comment')->LimitWordCount(50);
    }

    public function getContentNotifierLink() {
    	return "/admin/comments/edit/{$this->ID}";
    }

    public function getContentNotifierHeadLine() {
		return "/admin/comments/Comment/EditForm/field/Comment/item/{$this->ID}/edit";    
    }

}
```

### Configuration
In your ```_config/``` directory, first define some global settings:

```
ContentNotifierEmail:
  to: "me@example.com"
  from: notifications@example.com
  subject: Content has been updated on your site
  headline: New Content
```

Then, you can specify behaviours for each individual implementor of the Content Notifier extension.

```
Comment:
  ContentNotifier:
    batch_email: true
    delete_on_resolve: true
    auto_approve: UPDATED
JobListing:
  ContentNotifier:
    batch_email: true
    delete_on_resolve: true
    auto_approve: '*'
```

The following settigs are available:

* **batch_email** Don't notify of changes immediately. Use the ```ContentNotifierTask``` to deliver a batch email of updates. (Requires setting up a cron job)
* **delete_on_resolve** Once content is approved or denied, delete the ```ContentNotifierQueue``` record. (Recommended for tidiness)
* **auto_approve** Depending on the event, the content can auto-approve. The moderator of the content will still receive an email detailing the update, but the content is optimistically approved in advance rather than waiting for manual approval. Possible values: "*" (always approve), "UPDATED" (auto-approve updates), "CREATED" (auto-approve creates).

### ContentNotifierQueue

This is a polymorphic object that just displays readonly fields of the record that it points to for the admin to review. Approvals and Denials can be executed when looking at the queue record. These records are intended to be disposable, and there should never be a large population of them.

### ContentNotifierTask

Run ```/dev/tasks/ContentNotifierTask``` to send out the batch email of all the content that needs approval.

### ContentNotifierCleanTask

Run ```/dev/tasks/ContentNotifierCleanTask``` to bulk-delete any ContentNotifierQueue records that are no longer needed. You must include an ```action``` parameter in the request that contains one of the following values:
* "all" (delete all queue records)
* "approved" (delete all queue records that have been approved)
* "denied" (delete all queue records that have been denied)
* "orphaned" (delete all queue records that no longer point to an existing record)


## Turning off the filter

By default, anything that uses the ContentNotifierExtension will be hidden from a result set unless the user is an admin (see the ```admin_permission``` setting to customise). Occasionally, you may want to allow the record to be seen, for instance, when a user is editing his unapproved content. In that case, you can invoke ```ContentNotifierExtension::disable_filtering();````.


## How is this different from the Advanced Workflow module?

Most importantly, it doesn't contain nearly as many features, but more to the point, this module is intended to be used with user-generated content, or content that comes in via a thirdparty service, such as Meetup events. It provides a very simple way to ensure that your website isn't polluted with content that you don't necessarily want showing.
