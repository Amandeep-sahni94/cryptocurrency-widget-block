import {formatNumberIfLessThanOne,formatNumberWithSuffix} from './common';

const List=({ data })=>{
    const { showRank, showName,showPrice, show24hchanges, showVolume, showMarketcap, coinData,limit } = data;

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
     <table >
                        <thead>
                            <tr>
                                {showRank && <th>Rank</th>}
                                {showName && <th>Name</th>}
                                {showPrice && <th>Price</th>}
                                {show24hchanges && <th>24h%</th>}
                                {showVolume && <th>Volume</th>}
                                {showMarketcap && <th>Marketcap</th>}
                           
                            </tr>
                        </thead>
                        <tbody>
                            {displayedCoins.map((coin, index) => (
                               
                                <tr key={index}>
                                    {showRank && <td>{coin.rank}</td>}
                                    {showName && <td className='logo-name'><img src={`https://static.coinpaprika.com/coin/${coin.id}/logo.png`} /> {coin.name} {coin.symbol}</td>}

                                    {showPrice && <td>${formatNumberIfLessThanOne(coin.quotes.USD.price)}</td>}
                                    {show24hchanges && <td style={{color:(coin.quotes.USD.market_cap_change_24h<0?"red":"green")}}>{coin.quotes.USD.market_cap_change_24h}%</td>}
                                    {showVolume && <td>${formatNumberWithSuffix(coin.quotes.USD.volume_24h)}</td>}
                                    {showMarketcap && <td>${formatNumberWithSuffix(coin.quotes.USD.market_cap)}</td>}
                                   
                                </tr>
                            ))}
                        </tbody>
                    </table>
    </>

}
export default List;