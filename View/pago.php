<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../View/css/pago.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <title>Pago</title>
</head>
<body>
<main>
      <section id="card" class="card">
        <div id="highlight"></div>
        <section class="card__front">
          <div class="card__header">
            <div>CreditCard</div>
            <svg xmlns="http://www.w3.org/2000/svg" height="40" width="60" id="svg895" version="1.1" viewBox="-96 -98.908 832 593.448"><defs id="defs879"><style id="style877" type="text/css">.e{fill:#f79e1b}</style></defs><path id="rect887" display="inline" fill="#ff5f00" stroke-width="5.494" d="M224.833 42.298h190.416v311.005H224.833z"/><path id="path889" d="M244.446 197.828a197.448 197.448 0 0175.54-155.475 197.777 197.777 0 100 311.004 197.448 197.448 0 01-75.54-155.53z" fill="#eb001b" stroke-width="5.494"/><path id="path891" d="M621.101 320.394v-6.372h2.747v-1.319h-6.537v1.319h2.582v6.373zm12.691 0v-7.69h-1.978l-2.307 5.493-2.308-5.494h-1.977v7.691h1.428v-5.823l2.143 5h1.483l2.143-5v5.823z" class="e" fill="#f79e1b" stroke-width="5.494"/><path id="path893" d="M640 197.828a197.777 197.777 0 01-320.015 155.474 197.777 197.777 0 000-311.004A197.777 197.777 0 01640 197.773z" class="e" fill="#f79e1b" stroke-width="5.494"/></svg>
          </div>
          <div id="card_number" class="card__number">
            <span>#<br></span><span>#<br></span><span>#<br></span><span>#<br></span>
            <span>#<br></span><span>#<br></span><span>#<br></span><span>#<br></span>
            <span>#<br></span><span>#<br></span><span>#<br></span><span>#<br></span>
            <span>#<br></span><span>#<br></span><span>#<br></span><span>#<br></span>
          </div>
          <div class="card__footer">
            <div class="card__holder">
              <div class="card__section__title">Titular de la cuenta</div>
              <div id="card_holder">Nº de Tarjeta</div>
            </div>
            <div class="card__expires">
              <div class="card__section__title">Expires</div>
              <span id="card_expires_month">MM</span>/<span id="card_expires_year">YY</span>
            </div>
          </div>
        </section>
        <section class="card__back">
          <div class="card__hide_line"></div>

          <div class="card_cvv">
            <span>CVV</span>
            <div id="card_cvv_field" class="card_cvv_field"></div>
          </div>
        </section>
      </section>

      <form class="form" name="pago" method="POST" action="../Controller/usuario.php">
        <input type="hidden" name="formulario" value="pago">
        <div>
          <label for="number">Número de Tarjeta</label>
          <input id="number" type="number">
        </div>
        <div>
          <label for="holder">Titular de la cuenta</label>
          <input id="holder" type="text">
        </div>
        <div class="filed__group">
          <div>
            <label for="expiration_month">Fecha de Caducidad</label>
            <div class="filed__date">
              <select id="expiration_month">
                <option selected disabled>Mes</option>
                <option>01</option><option>02</option><option>03</option><option>04</option><option>05</option><option>06</option><option>07</option><option>08</option><option>09</option><option>10</option><option>11</option><option>12</option> </select>
              <select id="expiration_year">
                <option selected disabled>Año</option>
                <option>2023</option><option>2024</option><option>2025</option><option>2026</option><option>2027</option><option>2028</option><option>2029</option><option>2030</option><option>2031</option><option>2032</option>
              </select>
            </div>
          </div>
          <div>
            <label for="cvv">CVV</label>
            <input id="cvv" type="number">
          </div>
        </div>
        <?php
          $precioTotal = 0;
          foreach($_SESSION['productos'] as $llave => $value){
            if($llave !== 'rec'){ // No es necesario, pero por si acaso se ha colado el formulario en el array de prodcutos, lo quitamos
                $precioTotal = $precioTotal + (floatval($value['precio'])*intval($value['cantidad']));
            }
        }
          echo '<p>Total a pagar: '.$precioTotal.' €</p>';
        ?>
        <input type="submit" value="pagar" style="background-color: green;color: wheat;">
      </form>
    </main>
</body>
<script>
        document.getElementById("number").addEventListener("focus", (e) => {
            document.getElementById("card").classList.remove('flip')
            document.getElementById("highlight").className = 'highlight__number'
        })

        document.getElementById("holder").addEventListener("focus", (e) => {
            document.getElementById("card").classList.remove('flip')
            document.getElementById("highlight").className = 'highlight__holder'
        })

        document.getElementById("expiration_month").addEventListener("focus", (e) => {
            document.getElementById("card").classList.remove('flip')
            document.getElementById("highlight").className = 'highlight__expire'
        })

        document.getElementById("expiration_year").addEventListener("focus", (e) => {
            document.getElementById("card").classList.remove('flip')
            document.getElementById("highlight").className = 'highlight__expire'
        })

        document.getElementById("cvv").addEventListener("focus", (e) => {
            document.getElementById("card").classList.add('flip')
            document.getElementById("highlight").className = 'highlight__cvv'
        })

        document.getElementById("cvv").addEventListener("focusout", (e) => {
            document.getElementById("card").classList.remove('flip')
            document.getElementById("highlight").className = 'hidden'
        })

        let enteredCardNumbers = 0

        document.getElementById("number").addEventListener("input", (e) => {
            const value = e.target.value

            if(enteredCardNumbers > value.length) {
                document.getElementById('card_number').children[15 - (15 - value.length)].classList.remove('filed')
                document.getElementById('card_number').children[value.length].innerHTML = "#<br>"
            }
            else {
                if(value.length > 4 && value.length < 13) {
                    document.getElementById('card_number').children[value.length - 1].innerText += "*"
                }else {
                    document.getElementById('card_number').children[value.length - 1].innerText += value.slice(-1)
                }    

                document.getElementById('card_number').children[value.length - 1].classList.add('filed')
            }

            enteredCardNumbers = value.length

        })

        document.getElementById("holder").addEventListener("input", (e) => {
            document.getElementById('card_holder').innerText = e.target.value
        })

        document.getElementById("cvv").addEventListener("input", (e) => {
            document.getElementById('card_cvv_field').innerText = Array(e.target.value.length+1).join("*")
        })

        document.getElementById("expiration_month").addEventListener("change", (e) => {
            document.getElementById('card_expires_month').innerText = e.target.value
        })

        document.getElementById("expiration_year").addEventListener("change", (e) => {
            document.getElementById('card_expires_year').innerText = e.target.value.slice(-2)
        })
    </script>
</html>