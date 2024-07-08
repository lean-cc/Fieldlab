<h1 align="center">
 <br>
 Field lab
 <br>
</h1>

<h4 align="center">A field lab application to manage assignments.</h4>

<p align="center">
 <a href="#key-features">Key features</a> •
 <a href="#how-to-use">How to use</a> •
 <a href="#credits">Credits</a>
</p>

![image](https://github.com/lean-cc/Fieldlab/assets/114680621/09b0394b-f46e-406e-9724-06ac8efafebf)

## Key features

* Manage assignments
  - Create assignments as a teacher
  - Change assignments as a teacher
  - Delete assignments as a teacher
  - View and open assignments
* Change Password
* Manage students via the teachers panel
  - Register students
  - Changing class of students
* Register/unsubscribe from assignments

## How to use

You need:
- PHP 8.3.6
- Mysql 8.0.37

```bash
# Clone the repository
$git clone https://github.com/lean-cc/Fieldlab
# Import the SQL database
$ mysql -u root -p fieldlab < import.sql
# Run the application
$php -S localhost:80
```

## Feature request

- Dark mode
- Remove registration and deregistration to assignments for teachers
- See if a user is a teacher or a student at the admin panel
- Text field larger for add assignments

## Credits

- [Vaia](https://github.com/Vaia05)
- [Kars](https://github.com/lean-cc)
- [Lennard](https://github.com/kaasbaas08)
