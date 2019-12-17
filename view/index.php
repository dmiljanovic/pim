<?php
include '_partial/header.php';
?>

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
                        <option selected>Please select</option>
                        <?php foreach ($data as $rate) {?>
                            <option value="<?php echo $rate['code'] . '_' . $rate['rate'] ?>"><?php echo $rate['code'] ?></option>
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

<script>
    $(document).ready(function () {
        var rate = '';
        var quoteString = '';

        $("#get_rate").click(function(event) {
            event.preventDefault();

            var to = $("select#currency").val();
            quoteString = to.slice(0, 6);
            rate = to.slice(7);

            var amountToBuy = $("#amount_to_buy").val();
            var amountToPay = amountToBuy / rate;

            $("#amount_to_pay").val(amountToPay.toFixed(4));
            $("#info").removeClass('d-none');
        });

        $("#buy").click(function(event) {
            event.preventDefault();
            $.ajax({
                url: "save-data",
                type: "post",
                data: {
                    amount_to_buy: $("#amount_to_buy").val(),
                    amount_to_pay: $("#amount_to_pay").val(),
                    rate: rate,
                    quote_string: quoteString

                },
                success: function (resp) {
                    $("#info_alert").html( resp.message ).removeClass('d-none').delay(2500).slideUp(200, function() {
                        $(this).alert('close');
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
