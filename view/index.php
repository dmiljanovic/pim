<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
              integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T"
              crossorigin="anonymous">

        <title>P.I.M Test</title>
    </head>
    <body>
        <div class="container">
            <div class="alert alert-info mt-5 d-none" id="info_alert" role="alert">
            </div>
            <div class="row mt-5">
                <div class="col col-lg-5">
                    <form id="exchange">
                        <div class="form-group">
                            <label for="amount_to_buy">Amount To Buy</label>
                            <input type="number" class="form-control" id="amount_to_buy" min="1">
                        </div>
                        <div class="form-group">
                            <label for="currency">Currency</label>
                            <select class="custom-select" id="currency">
                                <option selected value="">Please select</option>
                                <?php foreach ($data as $rate) {?>
                                    <option value="<?php echo $rate['code'] . '_' . $rate['rate'] ?>"><?php echo substr($rate['code'], -3) ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <button type="submit" id="get_rate" class="btn btn-primary">Get Exchange Rate</button>
                    </form>
                </div>
                <div class="col col-lg-7 d-none" id="info">
                    <form id="info">
                        <div class="form-group">
                            <label for="amount_to_pay">Amount To Pay In USD</label>
                            <input type="number" readonly class="form-control" id="amount_to_pay" min="1">
                        </div>
                        <button type="submit" id="buy" class="btn btn-primary">Buy</button>
                    </form>
                </div>
            </div>
        </div>

        <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
                integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM"
                crossorigin="anonymous">
        </script>
        <script>
            $(document).ready(function () {
                var rate = '';
                var quoteString = '';
                var amountToBuy = '';
                var amountToPay = '';

                $("#amount_to_buy").on("change paste keyup", function() {
                    amountToBuy = $(this).val();

                    $("#amount_to_pay").val('');
                    $("#info").addClass('d-none');
                });

                $("select#currency").change(function() {
                    quoteString = $(this).val().slice(0, 6);
                    rate = $(this).val().slice(7);

                    $("#amount_to_pay").val('');
                    $("#info").addClass('d-none');
                });

                $("#get_rate").click(function(event) {
                    event.preventDefault();

                    var to = $("select#currency").val();

                    if (!to || !amountToBuy) {
                        $("#info_alert").html( '"Amount To Buy" and "Currency" are required!' ).removeClass('d-none');
                        setTimeout(function() {
                            $("#info_alert").addClass('d-none');
                        }, 2000);
                        return;
                    }

                    amountToPay = amountToBuy / rate;

                    $("#amount_to_pay").val(amountToPay.toFixed(4));
                    $("#info").removeClass('d-none');
                });

                $("#buy").click(function(event) {
                    event.preventDefault();

                    if (!quoteString || !amountToBuy || !amountToPay) {
                        $("#info_alert")
                            .html( '"Amount To Buy", "Currency" and "Amount To Pay In USD" are required!' )
                            .removeClass('d-none');
                        setTimeout(function() {
                            $("#info_alert").addClass('d-none');
                            $("#amount_to_pay").val('');
                            $("#info").addClass('d-none');
                        }, 2000);
                        return;
                    }
                    $.ajax({
                        url: "save-data",
                        type: "post",
                        data: {
                            amount_to_buy: amountToBuy,
                            amount_to_pay: amountToPay,
                            rate: rate,
                            quote_string: quoteString

                        },
                        success: function (resp) {
                            $("#info_alert").html( resp.message ).removeClass('d-none').delay(2500).slideUp(200, function() {
                                location.reload();
                            });
                        },
                        error: function (xhr) {
                            console.log(xhr.responseText);
                        }
                    });
                });
            });
        </script>
    </body>
</html>
