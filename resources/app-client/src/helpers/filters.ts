const filters = {
    currencyFormat(value: number) {
        return new Intl.NumberFormat("en-US", { style: "currency", currency: "USD" }).format(
          value
        );
    },
  }
  export default filters;