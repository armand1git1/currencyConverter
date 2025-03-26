<template>
  <div>
    <fieldset style="border: 2px solid lightblue; padding: 10px; margin-bottom: 10px;">
      <legend style="color: blue; font-weight: bold;">Currency Converter</legend>
      <p style="font-weight: bold; margin-bottom: 25px; color:red">Nb: Due to limited rights on the Api, Only conversion
        from euro is allowed.</p>

      <form @submit.prevent="convertCurrency">
        <div style="display: flex; align-items: center; margin-bottom: 10px;">
          <div style="margin-bottom: 10px; width:200px">
            <label for="fromCurrency">From Currency:</label>
          </div>

          <div style="margin-bottom: 7px; margin-left: 10px;">
            <select v-model="fromCurrency">
              <option value="EUR">EUR</option>
            </select>
          </div>
        </div>

        <div style="display: flex; align-items: center; margin-bottom: 10px;">
          <div style="margin-bottom: 10px; width:200px">
            <label for="toCurrency">To Currency:</label>
          </div>

          <div style="margin-bottom: 7px; margin-left: 10px;">           
              <select v-model="toCurrency">
              <option v-for="currency in currencies" :key="currency.code" :value="currency.code">
                {{ currency.code }} ({{ currency.name }})
              </option>
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
import AppScript from "./AppScript.js";
export default AppScript;
</script>
<style></style>
