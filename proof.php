<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Google Fonts -->
    <link
      rel="stylesheet"
      href="https://fonts.googleapis.com/css?family=Roboto:300,300italic,700,700italic"
    />

    <!-- CSS Reset -->
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.css"
    />

    <!-- Milligram CSS -->
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/milligram/1.4.1/milligram.css"
    />

    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Fetch Api Post</title>
    <style>
      h1 {
        text-align: center;
      }
    </style>
  </head>
  <body>
    <div class="container">
      <div class="row">
        <div class="column">
          <h1>How to send a post request using fetch</h1>
        </div>
      </div>
      <div class="row">
        <div class="column">
          <form>
            <fieldset>
              <label for="nameField">Name</label>
              <input
                name="name"
                type="text"
                placeholder="CJ Patoilo"
                id="nameField"
              />
              <label for="ageRangeField">Age Range</label>
              <select name="age" id="ageRangeField">
                <option value="0-13">0-13</option>
                <option value="14-17">14-17</option>
                <option value="18-23">18-23</option>
                <option value="24+">24+</option>
              </select>
              <label for="commentField">Comment</label>
              <textarea
                placeholder="Hi CJ …"
                name="comment"
                id="commentField"
              ></textarea>
              <div class="float-right">
                <input
                  name="sendToSelf"
                  value="1"
                  type="checkbox"
                  id="confirmField"
                />
                <label class="label-inline" for="confirmField"
                  >Send a copy to yourself</label
                >
              </div>
              <input class="button-primary" type="submit" value="Send" />
            </fieldset>
          </form>
        </div>
      </div>
    </div>
    <script>
      const url = "https://hookb.in/6Jpom3WKwquLbb031X6E";
      const formEl = document.querySelector("form");
      formEl.addEventListener("submit", async (e) => {
        e.preventDefault();
        const formData = new FormData(formEl);
        const formDataSerialized = Object.fromEntries(formData);
        const jsonObject = {
          ...formDataSerialized,
          sendToSelf: formDataSerialized.sendToSelf ? true : false,
        };
        try {
          const response = await fetch(url, {
            method: "POST",
            body: JSON.stringify(jsonObject),
            headers: {
              "Content-Type": "application/json",
            },
          });
          const json = await response.json();
          console.log(json);
        } catch (e) {
          console.error(e);
          alert("there as an error");
        }
      });
    </script>
  </body>
</html>