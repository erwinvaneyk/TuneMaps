function [J, grad] = logreg_l2(X, y, theta, lambda)

	% Number of training examples
	m = length(y);
	
	% Initialize output to zeroes
	J = 0;
	grad = zeros(size(theta));
	
	% Calculate the hypothesis
	h = sigmoid(X*theta);
	
	% Create a regularization theta that does not contain the first element
	newTheta = [0;theta(2:end);];
	
	% Calculate the regularized cost
	J = (1/m)*(-y'* log(h) - (1 - y)'*log(1-h)) + (lambda/(2*m))*newTheta'*newTheta;
	
	% Calculate the regularized partial gradients
	grad = (1/m)*(X'*(h-y)+lambda*newTheta);

end
