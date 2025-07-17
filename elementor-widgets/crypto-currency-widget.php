<?php
namespace ElementorAiosSlider\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
class CoinPaprika_Widget extends Widget_Base
{

    public function get_name()
    {
        return 'cryptocurrency-widget-block';
    }

    public function get_title()
    {
        return __('Cryptocurrency Widget', 'cryptocurrency-widget-block');
    }

    public function get_icon()
    {
        return 'eicon-price-table';
    }
    public function get_style_depends()
    {
        return ['crypto-style'];
    }

    protected function _register_controls()
    {
        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Settings', 'cryptocurrency-widget-block'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        // Widget Type
        $this->add_control(
            'widget_type',
            [
                'label' => __('Widget Type', 'cryptocurrency-widget-block'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'list',
                'options' => [
                    'list' => __('List', 'cryptocurrency-widget-block'),
                    'label' => __('Label', 'cryptocurrency-widget-block'),
                    'ticker' => __('Ticker', 'cryptocurrency-widget-block'),
                    'text' => __('Text', 'cryptocurrency-widget-block'),
                ],
            ]
        );


        // Show Coins
        $this->add_control(
            'limit',
            [
                'label' => __('Show Coins', 'cryptocurrency-widget-block'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 5,
                'options' => [
                    5 => __('Top 5', 'cryptocurrency-widget-block'),
                    10 => __('Top 10', 'cryptocurrency-widget-block'),
                    25 => __('Top 25', 'cryptocurrency-widget-block'),
                    50 => __('Top 50', 'cryptocurrency-widget-block'),
                    100 => __('Top 100', 'cryptocurrency-widget-block'),
                    'custom' => __('Custom Selection', 'cryptocurrency-widget-block'),
                ],
            ]
        );


        // Custom Coin Selection
        $this->add_control(
            'selected_coins',
            [
                'label' => __('Select Coins', 'cryptocurrency-widget-block'),
                'type' => \Elementor\Controls_Manager::SELECT2,
                'multiple' => true,
                'options' => $this->get_coin_options(),
                'condition' => ['limit' => 'custom'],
            ]
        );
        $this->add_control(
			'place_holder_text',
			[ 
				'label'       => esc_html__( 'Place Holder Text', 'aios' ),
				'default'     => esc_html__( '[coin-name] has a price of [coin-price] with a marketcap of [coin-marketcap] and ranked number [coin-rank] of all cryptocurrencies', 'aios' ),
				'type'        => Controls_Manager::WYSIWYG,
                'description'=>'([coin-name], [coin-price], [coin-marketcap], [coin-rank]) – These placeholders represent dynamic data. Please do not alter them.',
				'label_block' => true,
                'condition' => ['widget_type' => 'text'],
				
			]
		);

        // Ticker Speed
        $this->add_control(
            'ticker_speed',
            [
                'label' => __('Ticker Speed', 'cryptocurrency-widget-block'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [''],
                'range' => [
                    '' => [
                        'min' => 5,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],

                'default' => ['unit' => '', 'size' => 50],
                'condition' => ['widget_type' => 'ticker'],
            ]
        );

        // List Options
        $this->add_control(
            'show_name',
            [
                'label' => __('Show Name', 'cryptocurrency-widget-block'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
                'condition' => ['widget_type' => 'list'],
            ]
        );

        $this->add_control(
            'show_rank',
            [
                'label' => __('Show Rank', 'cryptocurrency-widget-block'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
                'condition' => ['widget_type' => 'list'],
            ]
        );
        $this->add_control(
            'show_24hchanges',
            [
                'label' => __('Show 24h%', 'cryptocurrency-widget-block'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
                'condition' => ['widget_type' => 'list'],
            ]
        );
        $this->add_control(
            'show_price',
            [
                'label' => __('Show Price', 'cryptocurrency-widget-block'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
                'condition' => ['widget_type' => 'list'],
            ]
        );
        $this->add_control(
            'show_volume',
            [
                'label' => __('Show Volume', 'cryptocurrency-widget-block'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
                'condition' => ['widget_type' => 'list'],
            ]
        );
        $this->add_control(
            'show_marketcap',
            [
                'label' => __('Show Marketcap', 'cryptocurrency-widget-block'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
                'condition' => ['widget_type' => 'list'],
            ]
        );

        // Add other list controls similarly...

        $this->end_controls_section();


        //style
        $this->start_controls_section(
            'section_style_layout',
            [
                'label' => __('Ticker', 'aios'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'text_color',
            [
                'label' => __('Color', 'aios'),
                'type' => Controls_Manager::COLOR,
                'default'=>'#000',
                'selectors' => [
                    '{{WRAPPER}} .wp-block-coinpaprika-block .ticker' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .wp-block-coinpaprika-block .ticker:hover' => 'animation-play-state: paused !important',
                ],
            ]

        );
        $this->add_control(
            'text_bg_color',
            [
                'label' => __('Background Color', 'aios'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wp-block-coinpaprika-block .ticker-wrapper' => 'background-color: {{VALUE}}',
                ],
            ]

        );
        $this->end_controls_section();
    }

    private function get_coin_options()
    {
        $coins = ccwfg_fetch_coin_data();
        $options = [];

        if ($coins) {
            foreach ($coins as $coin) {
                $options[$coin['id']] = $coin['name'];
            }
        }

        return $options;
    }



    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $coin_data = ccwfg_fetch_coin_data();

        if (!$coin_data) {
            echo '<div class="elementor-alert">' . __('Error loading cryptocurrency data.', 'cryptocurrency-widget-block') . '</div>';
            return;
        }

        // Process data based on limit
        if ('custom' === $settings['limit']) {
            $selected = $settings['selected_coins'] ?? [];
            $coin_data = array_filter($coin_data, function ($coin) use ($selected) {
                return in_array($coin['id'], $selected);
            });
        } else {
            $coin_data = array_slice($coin_data, 0, (int) $settings['limit']);
        }

        // Render based on widget type
        switch ($settings['widget_type']) {
            case 'list':
                $this->render_list($coin_data, $settings);
                break;
            case 'ticker':
                $this->render_ticker($coin_data, $settings);
                break;
            case 'label':
                ccwfg_display_coin_data_label($coin_data);
                break;
            case 'text':
                ccwfg_display_coin_data_text($coin_data,$settings['place_holder_text']);
                break;
                
            // Add other cases...
        }
    }

    private function render_list($data, $settings)
    {
        ?>
        <div class="coinpaprika-list">
            <table class="wp-block-coinpaprika-block">
                <thead>
                    <tr>
                        <?php if ('yes' === $settings['show_rank']): ?>
                            <th><?php _e('Rank', 'cryptocurrency-widget-block'); ?></th>
                        <?php endif; ?>
                        <?php if ('yes' === $settings['show_name']): ?>
                            <th><?php _e('Name', 'cryptocurrency-widget-block'); ?></th>
                        <?php endif; ?>
                        <?php if ('yes' === $settings['show_price']): ?>
                            <th><?php _e('Price', 'cryptocurrency-widget-block'); ?></th>
                        <?php endif; ?>
                        <?php if ('yes' === $settings['show_24hchanges']): ?>
                            <th><?php _e('24h%', 'cryptocurrency-widget-block'); ?></th>
                        <?php endif; ?>
                        <?php if ('yes' === $settings['show_volume']): ?>
                            <th><?php _e('Volume', 'cryptocurrency-widget-block'); ?></th>
                        <?php endif; ?>
                        <?php if ('yes' === $settings['show_marketcap']): ?>
                            <th><?php _e('Marketcap', 'cryptocurrency-widget-block'); ?></th>
                        <?php endif; ?>
                        <!-- Add other headers -->
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($data as $coin):
                        $usdprice=$coin['quotes']['USD'];
                        $changescolor=($usdprice['market_cap_change_24h']< 0)?'red':'green';
                        ?>
                        <tr>
                            <?php if ('yes' === $settings['show_rank']): ?>
                                <td><?php echo $coin['rank']; ?></td>
                            <?php endif; ?>
                            <?php if ('yes' === $settings['show_name']): ?>
                                <td class="logo-name">
                                    <img src="https://static.coinpaprika.com/coin/<?php echo esc_attr($coin['id']); ?>/logo.png"
                                        alt="<?php echo esc_attr($coin['name']) ?>">
                                    <?php echo esc_html($coin['name']) . ' ' . esc_html($coin['symbol']); ?>
                                </td>

                            <?php endif; ?>
                            <?php if ('yes' === $settings['show_price']): ?>
                                <td>$<?php echo ccwfg_format_number_if_less_than_one($coin['quotes']['USD']['price']); ?></td>
                            <?php endif; ?>
                            <?php if ('yes' === $settings['show_24hchanges']): ?>
                                <td style="color:<?php echo esc_attr($changescolor);?>"><?php echo $coin['quotes']['USD']['market_cap_change_24h']; ?></td>
                            <?php endif; ?>
                            <?php if ('yes' === $settings['show_volume']): ?>
                                <td>$<?php echo ccwfg_format_number_with_suffix($coin['quotes']['USD']['volume_24h']); ?></td>
                            <?php endif; ?>
                            <?php if ('yes' === $settings['show_marketcap']): ?>
                                <td>$<?php echo ccwfg_format_number_with_suffix($coin['quotes']['USD']['market_cap']); ?></td>
                            <?php endif; ?>
                            <!-- Add other columns -->
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php
    }

    private function render_ticker($data, $settings)
    {
        $speed = $settings['ticker_speed']['size'] ?? 50;
        ?>

        <div class="wp-block-coinpaprika-block">
            <div class="ticker-wrapper">
                <div class="ticker" style="animation: ticker <?php echo $speed; ?>s linear infinite;">
                    <div class="ticker-content">
                        <?php foreach ($data as $coin): ?>
                            <p>
                                <span class="label">
                                    <img src="https://static.coinpaprika.com/coin/<?php echo esc_attr($coin['id']); ?>/logo.png"
                                        alt="<?php echo esc_attr($coin['name']) ?>">
                                    <span><?php echo esc_html($coin['name']) . ' ' . esc_html($coin['symbol']); ?></span>
                                </span>
                                <span class="coin-name"><?php echo $coin['name']; ?></span>
                                <span
                                    class="coin-price">$<?php echo ccwfg_format_number_if_less_than_one($coin['quotes']['USD']['price']); ?></span>
                                </span>
                            </p>
                        <?php endforeach; ?>
                        <?php foreach ($data as $coin): ?>
                            <p>
                                <span class="label">
                                    <img src="https://static.coinpaprika.com/coin/<?php echo esc_attr($coin['id']); ?>/logo.png"
                                        alt="<?php echo esc_attr($coin['name']) ?>">
                                    <span><?php echo esc_html($coin['name']) . ' ' . esc_html($coin['symbol']); ?></span>
                                </span>
                                <span class="coin-name"><?php echo $coin['name']; ?></span>
                                <span
                                    class="coin-price">$<?php echo ccwfg_format_number_if_less_than_one($coin['quotes']['USD']['price']); ?></span>
                                </span>
                            </p>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }


}