<template>
  <div>
    <fieldset style="border: 2px solid lightblue; padding: 10px; margin-bottom: 10px;">
      <legend style="color: blue; font-weight: bold;">Currency Converter</legend>
      <p style="font-weight: bold; margin-bottom: 25px; color:red">Nb: Due to limited rights on the Api, Only conversion from euro is allowed.</p>

      <form @submit.prevent="convertCurrency">
      <div style="display: flex; align-items: center; margin-bottom: 10px;"> 
        <div style="margin-bottom: 10px; width:200px">
          <label for="fromCurrency">From Currency:</label>
        </div>  

        <div style="margin-bottom: 7px; margin-left: 10px;">
          <select v-model="fromCurrency">
            <option value="EUR">EUR</option>
            <!-- Add more currencies if needed -->
          </select>
        </div>
      </div>  

      <div style="display: flex; align-items: center; margin-bottom: 10px;"> 
        <div style="margin-bottom: 10px; width:200px">
          <label for="toCurrency">To Currency:</label>
        </div>
        
        <div style="margin-bottom: 7px; margin-left: 10px;">
          <select v-model="toCurrency">
            <option value="EUR">EUR</option>
            <option value="USD">USD</option>
            <!-- Add more currencies if needed -->
          </select>
        </div>
      </div>

      <div style="display: flex; align-items: center; margin-bottom: 10px;"> 

        <div style="margin-bottom: 10px; width:200px">
          <label for="amount">Amount:</label>
        </div>
        <div style="margin-bottom: 7px; margin-left: 10px;">
          <input type="text" v-model="amount" @input="formatAmount" placeholder="e.g., 1,23" />
        </div>
      </div>

        <button type="submit">Convert</button>
      </form>
    </fieldset>

    <fieldset style="border: 2px solid lightblue; padding: 10px; background-color: #f0f8ff;">
      <legend style="color: blue; font-weight: bold;">Converted Amount</legend>
      <div>{{ convertedValue ? `${convertedValue} (${currencyconvertedName})` : "Awaiting conversion..." }}</div>
    </fieldset>

    <div v-if="errorMessage" style="color: red; margin-top: 20px;">
      {{ errorMessage }}
    </div>
  </div>
</template>


<script>
export default {
  data() {

    return {
      fromCurrency: "EUR",
      toCurrency: "",
      amount: null,
      convertedValue: null,
      currencyconvertedName: null,
      errorMessage: "",
    };
  },
  methods: {
    async convertCurrency() {
      try {
        if (!this.amount || this.amount.trim() === "") {
          this.errorMessage = "Amount cannot be empty.";
          this.convertedValue = null;
          this.currencyconvertedName= null;
          return; // Stop execution if the amount is empty
        }


        this.formatAmount({ target: { value: this.amount } });
        // this.formattedAmount = this.amount; // Store the formatted amount
        const [integerPart, decimalPart] = this.formatAmount({ target: { value: this.amount } });

        // Log the integer and decimal parts for debugging
        console.log("Integer Part:", integerPart);
        console.log("Decimal Part:", decimalPart);

        const response = await fetch(
          //`https://api.example.com/convert?from=${this.fromCurrency}&to=${this.toCurrency}&amount=${this.amount}`
          `https://site.walkap.net/currencyConverter/backend-currencyconverter/converter/read.php?cur1=${this.fromCurrency}&cur2=${this.toCurrency}&amount=${integerPart}&decimal=${decimalPart}`
          //`https://api.example.com/convert?from=${this.fromCurrency}&to=${this.toCurrency}&amount=${this.amount}`

        );
        const data = await response.json();
        // if (response.ok && data.success) {
        if (response.ok) {
          this.convertedValue = data.formatedAmount;
          this.currencyconvertedName = data.currencyName;
          this.errorMessage = "";
        } else {
          this.convertedValue = null;
          this.errorMessage = "Invalid currency or conversion failed.";
        }
      } catch (error) {
        this.errorMessage = "An error occurred during the conversion.";
        this.convertedValue = null;
      }
    },
    formatAmount(myAmount) {
      // Replace any non-numeric characters except for the comma
      let value = myAmount.target.value.replace(/[^0-9,]/g, "");

      // Ensure only one comma is present
      const parts = value.split(",");
      if (parts.length > 2) {
        value = parts[0] + "," + parts[1].slice(0, 2); // Keep only the first two decimal places
      }

      // Limit to two decimal places after the comma
      if (parts[1]) {
        value = parts[0] + "," + parts[1].slice(0, 2);
      }

      // Update the input value
      this.amount = value;
      const integerPart = parts[0] || "0"; // Default to "0" if no integer part
      const decimalPart = parts[1] || "0"; // Default to "00" if no decimal part
      return [integerPart, decimalPart];
    },
  },
};
</script>

<style>
/* Add custom styling here if needed */
</style>
