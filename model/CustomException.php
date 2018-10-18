<?php

class Exception500 extends Exception {}

class PasswordsDoNotMatchException extends Exception {}

class MissingUsernameException extends Exception {}

class MissingPasswordException extends Exception {}

class UsernameTooShortException extends Exception {}

class UsernameTooLongException extends Exception {}

class PasswordTooShortException extends Exception {}

class WrongUsernameOrPasswordException extends Exception {}

class OccupiedUsernameException extends Exception {}

class HtmlCharacterException extends Exception {}

class ForbiddenException extends Exception {}