import {formatNumberIfLessThanOne,formatNumberWithSuffix} from './common';

const Ticker=({ data })=>{
    const {coinData,limit,tickerspeed } = data;

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
        <style>
        {`
          .wp-block-coinpaprika-block .ticker {
           animation: ticker ${tickerspeed}s linear infinite;
          }
          .wp-block-coinpaprika-block .ticker:hover {
            animation-play-state: paused;
          }
        `}
      </style>
            <div className="ticker-wrapper">
            <div className="ticker">
            <div className="ticker-content">
            {displayedCoins.map((coin, index) => (
                    <p key={index}>
              
                    <span className="label" ><img src={`https://static.coinpaprika.com/coin/${coin.id}/logo.png`} /> {coin.name} {coin.symbol}</span>
                    <span className="value">${formatNumberIfLessThanOne(coin.quotes.USD.price)}</span>                              
                    
                </p>                          
            ))}
            {displayedCoins.map((coin, index) => (
                    <p key={index}>
              
                    <span className="label" ><img src={`https://static.coinpaprika.com/coin/${coin.id}/logo.png`} /> {coin.name} {coin.symbol}</span>
                    <span className="value">${formatNumberIfLessThanOne(coin.quotes.USD.price)}</span>                                
                    
                </p>                          
            ))}
            </div>
            </div>
            </div>
                      
    </>

}
export default Ticker;