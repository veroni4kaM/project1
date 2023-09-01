import {useState} from "react";

function Counter({value = 0, color="yellow"}) {
    const [currentValue, setValue] = useState(value);
    return (<div style={{backgroundColor:(currentValue < 0 ?color='#8092FF' : color='#FFF385')}}>
            <div>Value: {currentValue}</div>
            <div>
                <button onClick={()=>{
                    setValue(currentValue + 1)
                }}>+</button>
                <button onClick={()=>{
                    setValue(currentValue - 1)
                }}>-</button>
            </div>
        </div>);
}
/*Counter.defaultProps = {
    value:100
}*/
export default Counter;