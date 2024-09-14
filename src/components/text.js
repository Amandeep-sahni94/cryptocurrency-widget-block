import { RichText } from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n';

const Text = ({ data,setAttributes }) => {
    const { coinData, limit ,Textwidget} = data;

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
    const onChangeStarttext = (value) => {
        setAttributes({ Textwidget: value });
    };
  

    // Example usage
    const displayedCoins = displayCoins();

    return <>
        <div className="coin-text-wrap">
            {displayedCoins.map((coin, index) => (
                <div className="coin-text-inner" key={index}>
                    <span style={{color:"red"}}>{coin.name}([coin-name], [coin-price], [coin-marketcap], [coin-rank]) – These placeholders represent dynamic data. Please do not alter them.<br/><br/></span>
                    <RichText
                        tagName="div"
                        placeholder={__(
                            'Write About the coins',
                            'block-development-examples'
                        )}
                        value={Textwidget}
                        onChange={onChangeStarttext}
                    />
                   
                    
                </div>
            ))}
        </div>

    </>

}
export default Text;