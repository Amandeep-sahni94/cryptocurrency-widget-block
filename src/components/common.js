
export const formatNumberIfLessThanOne = (number) => {
    // Ensure the number is a float
    number = parseFloat(number);

    // Check if the number is less than 1
    if (number < 0.01 && number !== 0) {
        // Convert the number to a string
        let numberStr = number.toString();

        // Check if there is a decimal point in the number
        if (numberStr.includes('.')) {
            // Split the number into integer and fractional parts
            let [integerPart, fractionalPart] = numberStr.split('.');

            // Remove trailing zeros from the fractional part
            fractionalPart = fractionalPart.replace(/0+$/, '');

            // Format the number with the detected decimal places
            return `${integerPart}.${fractionalPart}`;
        } else {
            // No decimal part
            return numberStr;
        }
    } else {
        // If the number is 1 or greater, format as integer
        return number.toFixed(2);
    }
}

export const formatNumberWithSuffix = (number) => {
    // Ensure the number is a float
    number = parseFloat(number);

    // Handle negative numbers
    const isNegative = number < 0;
    number = Math.abs(number);

    // Define suffixes and corresponding thresholds
    const suffixes = [
        { value: 1e12, suffix: 'T' }, // Trillion
        { value: 1e9, suffix: 'B' },  // Billion
        { value: 1e6, suffix: 'M' },  // Million
        { value: 1e3, suffix: 'K' },  // Thousand
    ];

    // Format the number with appropriate suffix
    for (const { value, suffix } of suffixes) {
        if (number >= value) {
            return (isNegative ? '-' : '') + (number / value).toFixed(2) + suffix;
        }
    }

    // Fallback for numbers smaller than 1000
    return (isNegative ? '-' : '') + number.toFixed(2);
}