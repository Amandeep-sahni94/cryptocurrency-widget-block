import {formatNumberIfLessThanOne} from './common';

const Label=({ data })=>{
    const { coinData,limit } = data;

    const displayCoins = () => {
        // Check if limit is "custom"
        if (limit === "custom") {
          return coinData; // Return all data when limit is "custom"
        } else {
          // Convert limit to a number and use it to slice coinData
          const numericLimit = parseInt(limit, 10);
          return coinData.slice(0, numericLimit); // Slice coinData based on the limit
        }
      };

// Example usage
const displayedCoins = displayCoins();

    return<>
            <div className={`coin-container`}>
            {displayedCoins.map((coin, index) => (
                    <div className={`coin-stats`} key={index}>              
                     <span className={`label`} ><img src={`https://static.coinpaprika.com/coin/${coin.id}/logo.png`} /> {coin.name} {coin.symbol}</span>
                     <span className={`value`}>${formatNumberIfLessThanOne(coin.quotes.USD.price)}</span>          
                </div>                          
            ))}
            </div>
                      
    </>

}
export default Label;