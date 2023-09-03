import {useState} from "react";
import styled from "styled-components";

const Container = styled.div`
  background-color: ${(props) => (props.isNegative ? "#8092FF" : "#FFF385")};
  padding: 10px;
  margin: 10px;
`;

const Value = styled.div`
  font-size: 24px;
`;

const ButtonContainer = styled.div`
  display: flex;
  margin-top: 10px;
`;

const Button = styled.button`
  padding: 5px 10px;
  font-size: 18px;
`;

function Counter({ initialValue = 0 }) {
    const [value, setValue] = useState(initialValue);

    const handleIncrement = () => {
        setValue(value + 1);
    };

    const handleDecrement = () => {
        setValue(value - 1);
    };

    return (
        <Container isNegative={value < 0}>
            <Value>Value: {value}</Value>
            <ButtonContainer>
                <Button onClick={handleIncrement}>+</Button>
                <Button onClick={handleDecrement}>-</Button>
            </ButtonContainer>
        </Container>
    );
}
export default Counter;