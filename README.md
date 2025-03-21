## Task #2

I prefer to keep my controllers as light and let them handle the Request data.

Database queries and data manipulation, I usually isolate in Actions.

I've been using the https://www.laravelactions.com/, but simple classes do the job as well when you don't want to include another package in your project.

Getting all the data from the database at once if very bad practice. The data must be paginated and returned to the view.
Otherwise, it will slowly eat up all the memory as the application grows.

## Task #3
All the tests can be found in the **SpreadsheetServiceTest** class

## Task #4
The collection problem is solved in the **EmployeeOfficeDistributionCommand** class
You can run it using the command `php artisan app:employee-office-distribution-command`

## Task #5
**A)** It schedules the command `app:example-command` to run in the background, every hour. 
It will run without overlapping in a multi server environment, and it will only run on one server.

**B)** -

**C)** `$query->update()`: executes a direct database update. 
It should be used in bulk updated, where performance is key. This will not fire any events. 

`$model->update()`: updated a single eloquent model with event firing and model features (mutators, timestamps)

`$model->updateQuietly()`: updates a single eloquent model without firing any events (side effects).
It will still use the model feature like mutators and timestamps

