import { __ } from '@wordpress/i18n';
import { PanelBody, ToggleControl, RangeControl, Spinner, Placeholder, SelectControl } from '@wordpress/components';
import { useState, useEffect } from '@wordpress/element';
import { useBlockProps,RichText,InspectorControls} from '@wordpress/block-editor';
import { useSelect } from '@wordpress/data';
import List from './components/list';
import Label from './components/label';
import Ticker from './components/ticker';
import Text from './components/text';
import Select from 'react-select';

import './editor.scss';
const Edit = ({ attributes, setAttributes }) => {

    const blockProps = useBlockProps();

    const {
        showName,
        showVolume,
        showRank,
        showPrice,
        show24hchanges,
        limit,
        showMarketcap,
        widgettype,
        selectedCoins,
        removeStyle,
        tickerspeed,
        Textwidget       
        // Add more attributes for other columns
    } = attributes;
    const [coinOriginalData, setCoinOriginalData] = useState(null);
    const [coinData, setCoinData] = useState(null);

    const siteURL = useSelect(
        (select) => {
            const core = select('core');

            const site = core.getSite();
            return site && site.url;
        },
        []
    );

    useEffect(() => {
        const fetchData = async () => {
            try {
                if (siteURL) {
                    const response = await fetch(`${siteURL}/wp-json/coin-paprika/v1/coin-data`);
                    if (!response.ok) {
                        throw new Error('Failed to fetch data');
                    }
                    const data = await response.json();
                    setCoinData(data); // Assuming setCoinData is a function to update state
                    setCoinOriginalData(data);
                }
            } catch (error) {
                console.error('Error fetching data:', error);
                // Handle error state or display error message
            }
        };

        fetchData();
    }, [siteURL, setCoinData]); // Include setCoinData in dependency array if it is a state setter function


    const onChangeShowName = (value) => {
        setAttributes({ showName: value });
    };

    const onChangeShowVolume = (value) => {
        setAttributes({ showVolume: value });
    };
    const onChangeShowMarketcap = (value) => {
        setAttributes({ showMarketcap: value });
    };

    const onChangeShowRank = (value) => {
        setAttributes({ showRank: value });
    };
    const onChangeShowPrice = (value) => {
        setAttributes({ showPrice: value });
    };
    const onChangeRemoveStyle = (value) => {
        setAttributes({ removeStyle: value });
    };
    const onChangeShow24hChange = (value) => {
        setAttributes({ show24hchanges: value });
    };
    const onChangeTickerSpeed = (value) => {
        setAttributes({ tickerspeed: value });
    };
    const onChangeLimit = (value) => {
        if (value != "custom") {
            setCoinData(coinOriginalData);
        }
        else {
            if (selectedCoins && coinOriginalData && value == "custom") {
                const result = filterSelectedCoins(selectedCoins, coinOriginalData);

                if (result) {
                    setCoinData(result);
                }
            }
            else {
                setCoinData(null);
            }
        }
        setAttributes({ limit: value });
        //setCoinData(prevCoinData => prevCoinData.slice(0, value));
    };
    const onChangeType = (value) => {
        setAttributes({ widgettype: value });
        //setCoinData(prevCoinData => prevCoinData.slice(0, value));
    };
    const onChangeCustomCoins = (value) => {
        setAttributes({ selectedCoins: value });
        //setCoinData(prevCoinData => prevCoinData.slice(0, value));
    };

    const createOptions = (coinData) => {
        return coinData.map((coin) => ({
            label: `${coin.name}`,
            value: coin.id
        }));
    };
    const filterSelectedCoins = (selectedValues, allData) => {
        // Create a map of all data by value for quick lookup
        const dataMap = new Map(allData.map(item => [item.id, item]));

        // Filter the allData array to include only items with IDs in selectedValues
        const filteredData = selectedValues.map(selected => dataMap.get(selected.value))
            .filter(item => item !== undefined);

        return filteredData;
    };
    useEffect(() => {
        if (selectedCoins && coinOriginalData && limit == "custom") {
            const result = filterSelectedCoins(selectedCoins, coinOriginalData);

            if (result) {
                setCoinData(result);
            }
        }
    }, [coinOriginalData, selectedCoins]);



    return (
        <div {...blockProps}>
            <InspectorControls>
                <PanelBody title={__('Widget Settings', 'ccwfg')}>
                    <SelectControl
                        label="Widget Type"
                        value={widgettype}
                        options={[
                            { label: 'List', value: 'list' },
                            { label: 'Label', value: 'label' },
                            { label: 'Ticker', value: 'ticker' },
                            { label: 'Text', value: 'text' },

                        ]}
                        onChange={onChangeType}
                        __nextHasNoMarginBottom
                    />
                    <SelectControl
                        label="Show Coins"
                        value={limit}
                        options={[
                            { label: 'Top 5', value: 5 },
                            { label: 'Top 10', value: 10 },
                            { label: 'Top 25', value: 25 },
                            { label: 'Top 50', value: 50 },
                            { label: 'Top 100', value: 100 },
                            { label: 'Custom', value: 'custom' },
                        ]}
                        onChange={onChangeLimit}
                        __nextHasNoMarginBottom

                    />
                    {coinOriginalData && limit == "custom" && (
                        <Select
                            isMulti
                            isSearchable
                            onChange={onChangeCustomCoins}
                            name="Select coins"
                            options={createOptions(coinOriginalData)}
                            defaultValue={selectedCoins}
                            closeMenuOnSelect={false}

                        />
                    )
                    }
                    {/* {widgettype == "label" && (
                        <ToggleControl
                            label={__('Remove Style', 'ccwfg')}
                            checked={removeStyle}
                            onChange={onChangeRemoveStyle}
                        />)} */}
                  
                     {widgettype == "ticker"&& (
                        <RangeControl
                        __nextHasNoMarginBottom
                        label={__('Ticker Speed', 'ccwfg')}
                        value={ tickerspeed }
                        onChange={onChangeTickerSpeed}
                        min={ 5 }
                        max={ 100 }
                    />
                       
                    )}

                    {widgettype == "list" && (
                        <>
                            <ToggleControl
                            label={__('Show Name', 'ccwfg')}
                            checked={showName}
                            onChange={onChangeShowName}
                            />
                            <ToggleControl
                                label={__('Show Rank', 'ccwfg')}
                                checked={showRank}
                                onChange={onChangeShowRank}
                            />
                            <ToggleControl
                                label={__('Show Price', 'ccwfg')}
                                checked={showPrice}
                                onChange={onChangeShowPrice}
                            />
                            <ToggleControl
                                label={__('Show 24h Changes', 'ccwfg')}
                                checked={show24hchanges}
                                onChange={onChangeShow24hChange}
                            />
                            <ToggleControl
                                label={__('Show Volume', 'ccwfg')}
                                checked={showVolume}
                                onChange={onChangeShowVolume}
                            />
                            <ToggleControl
                                label={__('Show Marketcap', 'ccwfg')}
                                checked={showMarketcap}
                                onChange={onChangeShowMarketcap}
                            />

                        </>
                    )}
                </PanelBody>
            </InspectorControls>

            <div className="coinpaprika-table-preview">
                {coinData && (
                    <div className="wp-block-coinpaprika-block">
                        {widgettype == "list" && (
                            <List data={{ showRank, showName, showPrice, show24hchanges, showVolume, showMarketcap, coinData, limit }} />
                        )}
                        {widgettype == "label" && (
                            <Label data={{coinData, limit}} />
                        )}
                        {widgettype == "ticker" && (
                            <Ticker data={{ coinData, limit,tickerspeed }} />
                        )}
                         {widgettype == "text" && (
                            <Text data={{ coinData, limit,Textwidget}} setAttributes={setAttributes} />
                        )}
                    </div>
                )}
                {!coinData && (<Placeholder icon={<Spinner />} label={(limit == "custom" && !selectedCoins ? "Please select a coin" : "Loading...")}></Placeholder>)}
            </div>
        </div>
    );
};

export default Edit;
