// filepath: c:\laragon\www\currencyConverter\frontend-currencyconvertor\currency-convertor-app\src\AppScript.js
export default {
    data() {
      return {
        fromCurrency: "EUR",
        toCurrency: "",
        amount: null,
        convertedValue: null,
        currencyconvertedName: null,
        errorMessage: "",
        currencies: [], // Array to store the list of currencies
      };
    },
    methods: {
      async fetchCurrencies() {
        try {
          // Fetch the availableCurrencies.json file
          const response = await fetch("./data/availableCurrencies.json");
          const data = await response.json();
  
          if (response.status === 200 && data.data) {
            // Populate the currencies array with the data from the JSON file
            this.currencies = data.data.filter(currency => currency.active); // Only include active currencies
          } else {
            this.errorMessage = "Failed to load currencies.";
          }
        } catch (error) {
          this.errorMessage = "An error occurred while loading currencies.";
          console.error(error);
        }
      },
  
      async convertCurrency() {
        try {
          if (!this.amount || this.amount.trim() === "") {
            this.errorMessage = "Amount cannot be empty.";
            this.convertedValue = null;
            this.currencyconvertedName = null;
            return; // Stop execution if the amount is empty
          }
  
          this.formatAmount({ target: { value: this.amount } });
          const [integerPart, decimalPart] = this.formatAmount({ target: { value: this.amount } });
  
          const response = await fetch(
            `https://site.walkap.net/currencyConverter/backend-currencyconverter/converter/read.php?cur1=${this.fromCurrency}&cur2=${this.toCurrency}&amount=${integerPart}&decimal=${decimalPart}`
          );
          const data = await response.json();
  
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
        let value = myAmount.target.value.replace(/[^0-9,]/g, "");
        const parts = value.split(",");
        if (parts.length > 2) {
          value = parts[0] + "," + parts[1].slice(0, 2);
        }
        if (parts[1]) {
          value = parts[0] + "," + parts[1].slice(0, 2);
        }
        this.amount = value;
        const integerPart = parts[0] || "0";
        const decimalPart = parts[1] || "0";
        return [integerPart, decimalPart];
      },
  
      async fetchUpdateCurrencylist() {
        try {
          const response = await fetch(
            `http://localhost/currencyConverter/backend-currencyconverter/converter/read.php?method=POST`
          );
          const data = await response.json();
  
          if (response.ok) {
            const jsonData = JSON.stringify(data, null, 2);
            const blob = new Blob([jsonData], { type: "application/json" });
            const url = URL.createObjectURL(blob);
  
            const link = document.createElement("a");
            link.href = url;
            link.download = "availableCurrencies.json";
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
  
            this.errorMessage = "";
          } else {
            this.errorMessage = "Failed to fetch currency data.";
          }
        } catch (error) {
          this.errorMessage = "An error occurred while fetching currency data.";
        }
      },
    },
  
    mounted() {
      this.fetchCurrencies();
    },
  };