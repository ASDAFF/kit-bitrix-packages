import React from 'react';
import './quantity-count.scss';

export default class QuantityCount extends React.Component {

    constructor(props) {
        super(props);

        this.onBtnMore = () => {
            this.props.onBtnMore(this.props.PRODUCT_ID)
        };

        this.onBtnLess = () => {
            this.props.onBtnLess(this.props.PRODUCT_ID)
        };

        this.onChangeCount = (e) => {
            const maxCount = this.props.maxCount;
                let count = e.target.value.replace(/\D/g, '');

                if (parseInt(count) > parseInt(maxCount)) {
                    count = maxCount;
                }
                if (parseInt(count) < this.props.ratio) {
                    count = this.props.ratio;
                }

            this.props.onChangeCount(this.props.PRODUCT_ID, count);
        };

        this.onBlurCount = (e) => {
            this.props.onBlurCount(this.props.PRODUCT_ID, e.target.value);
        };
    };



    render() {
        return (
            <div className={`basket-item__quantity-count quantity-count`}>
                <button className={`quantity-count__btn quantity-count__btn--less`}
                        onClick={this.onBtnLess} dangerouslySetInnerHTML={ {__html: '&minus;'}}></button>
                <input type="text"
                       className={`quantity-count__input`}
                       onChange={this.onChangeCount}
                       onBlur={this.onBlurCount}
                       value={this.props.itemCount}
                       />
                <button className={`quantity-count__btn quantity-count__btn--more`}
                        onClick={this.onBtnMore}> + </button>
            </div>
        );
    }

}
