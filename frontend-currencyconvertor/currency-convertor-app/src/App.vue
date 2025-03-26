<template>
  <div>
    <fieldset style="border: 2px solid lightblue; padding: 10px; margin-bottom: 20px;">
      <legend style="color: blue; font-weight: bold;">Currency Converter</legend>
      <form @submit.prevent="convertCurrency">
        <div style="margin-bottom: 10px;">
          <label for="fromCurrency">From Currency:</label>
          <select v-model="fromCurrency">
            <option value="EUR">EUR</option>
            <option value="USD">USD</option>
            <!-- Add more currencies if needed -->
          </select>
        </div>
        <div style="margin-bottom: 10px;">
          <label for="toCurrency">To Currency:</label>
          <select v-model="toCurrency">
            <option value="EUR">EUR</option>
            <option value="USD">USD</option>
            <!-- Add more currencies if needed -->
          </select>
        </div>
        <div style="margin-bottom: 10px;">
          <label for="amount">Amount:</label>
          <input type="text" v-model="amount" @input="formatAmount"
          placeholder="e.g., 1,23" />
        </div>
        <button type="submit">Convert</button>
      </form>
    </fieldset>

    <fieldset style="border: 2px solid lightblue; padding: 10px; background-color: #f0f8ff;">
      <legend style="color: blue; font-weight: bold;">Converted Amount</legend>
      <div>{{ convertedValue || "Awaiting conversion..." }}</div>
    </fieldset>

    <div v-if="errorMessage" style="color: red; margin-top: 20px;">
      {{ errorMessage }}
    </div>
  </div>
</template>

 "base_currency": "EUR",
    "quote_currency": "USD",
    "amount": 200,
    "quote": 1.082836,
    "date": "2025-03-25",
    "convertedAmount": 216.56719999999999,
    "formatedAmount": "216,57 $"
<script>
export default {
  data() {
    /*
    return {
      fromCurrency: "",
      toCurrency: "",
      amount: null,
      convertedValue: null,
      errorMessage: "",
    };
    */
    return {
      fromCurrency: "",
      toCurrency: "",
      amount: null,
      convertedValue: null,
      errorMessage: "",
    };
  },
  methods: {
    async convertCurrency() {
      try {
        const response = await fetch(
          //`https://api.example.com/convert?from=${this.fromCurrency}&to=${this.toCurrency}&amount=${this.amount}`
          `https://site.walkap.net/currencyConverter/backend-currencyconverter/converter/read.php?cur1=${this.fromCurrency}&cur2=${this.toCurrency}&amount=${this.amount}&decimal=0`
          //`https://api.example.com/convert?from=${this.fromCurrency}&to=${this.toCurrency}&amount=${this.amount}`

        );
        const data = await response.json();
        // if (response.ok && data.success) {
          if (response.ok) {
          this.convertedValue = data.formatedAmount;
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
    formatAmount(event) {
    // Replace any non-numeric characters except for the comma
    let value = event.target.value.replace(/[^0-9,]/g, "");

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
  },
  },
};
</script>

<style>
/* Add custom styling here if needed */
</style>
