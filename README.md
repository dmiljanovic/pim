# PIM

* This mini project is a response to the task.

## Criteria

* A web application must be created that will allow a user to purchase foreign currencies.
* The page should display the available currencies for selection by the user.
* The page should have input where the user can get a quote by entering the amount of the
currency that they wish to exchange.
* Once the user has entered either amount and selected the foreign currency, the necessary
calculation needs to be done that will display the amount they need to pay in USD
* With the calculated amount displayed, the user can then select to “purchase” the foreign
currency. An “order” for the currency must be saved to the database and the user must be
shown a confirmation.

## Details

* The currency used for payment with be US Dollars (USD).
* The currencies that can be purchased are:
Japanese Yen (JPY)
British Pound (GBP)
Euro (EUR)
* Use the following exchange rates:
    * USD to JPY: 108.55
    * USD to GBP: 0.77816
    * USD to EUR: 0.87411
* A surcharge must be added to orders and differs for the currencies:
    * JPY: 7.5%
    * GBP: 5%
    * EUR: 5%
* The following information must be saved with an order:
    * Foreign currency purchased.
    * Exchange rate for foreign currency.
    * Surcharge percentage.
    * Amount of surcharge.
    * Amount of foreign currency purchased.
    * Amount paid in USD.
    * Discount percentage
    * Discount amount
    * Date created.
* When an order is saved the following extra actions need to be taken for the different
currencies:
    * PY: No action.
    * GBP: Send an email with order details. This can be a basic text or html email to any
configurable email address.
    * EUR: Apply a 2% discount on the total order amount (this needs to be configurable for
the currency).

# Instruction to install

* Clone repo
* To install dependencies please run "composer install"
* To create database and tables please run sql script (db.sql).
* To start import of exchange rates go to "/import-rates".
* For testing mail sending, in "app/Helpers/Mail.php" change email address, at line 30, where an email should be sent.

Enjoy :)